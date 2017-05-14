<?php
/**
 * ownCloud - gpxedit
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <eneiluj@gmx.fr>
 * @copyright Julien Veyssier 2015
 */

namespace OCA\GpxEdit\Controller;

use OCP\App\IAppManager;

use OCP\IURLGenerator;
use OCP\IConfig;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\RedirectResponse;

use OCP\AppFramework\Http\ContentSecurityPolicy;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Controller;

/**
 * Recursive find files from name pattern
 */
function globRecursive($path, $find, $recursive=True) {
    $result = Array();
    $dh = opendir($path);
    while (($file = readdir($dh)) !== false) {
        if (substr($file, 0, 1) === '.') continue;
        $rfile = "{$path}/{$file}";
        if (is_dir($rfile) and $recursive) {
            foreach (globRecursive($rfile, $find) as $ret) {
                array_push($result, $ret);
            }
        } else {
            if (fnmatch($find, $file)){
                array_push($result, $rfile);
            }
        }
    }
    closedir($dh);
    return $result;
}

/*
 * search into all directories in PATH environment variable
 * to find a program and return it if found
 */
function getProgramPath($progname){
    $path_ar = explode(':',getenv('path'));
    $path_ar = array_merge($path_ar, explode(':',getenv('PATH')));
    foreach ($path_ar as $path){
        $supposed_gpath = $path.'/'.$progname;
        if (file_exists($supposed_gpath) and
            is_executable($supposed_gpath)){
            return $supposed_gpath;
        }
    }
    return null;
}

function endswith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

class UtilsController extends Controller {


    private $userId;
    private $userfolder;
    private $config;
    private $userAbsoluteDataPath;
    private $dbconnection;
    private $dbtype;

    public function __construct($AppName, IRequest $request, $UserId,
        $userfolder, $config, IAppManager $appManager){
        parent::__construct($AppName, $request);
        $this->userId = $UserId;
        $this->dbtype = $config->getSystemValue('dbtype');
        // IConfig object
        $this->config = $config;
        if ($this->dbtype === 'pgsql'){
            $this->dbdblquotes = '"';
        }
        else{
            $this->dbdblquotes = '';
        }
        if ($UserId !== '' and $userfolder !== null){
            // path of user files folder relative to DATA folder
            $this->userfolder = $userfolder;
            // absolute path to user files folder
            $this->userAbsoluteDataPath =
                $this->config->getSystemValue('datadirectory').
                rtrim($this->userfolder->getFullPath(''), '/');

            // make cache if it does not exist
            $cachedirpath = $this->userAbsoluteDataPath.'/../cache';
            if (! is_dir($cachedirpath)){
                mkdir($cachedirpath);
            }

            $this->dbconnection = \OC::$server->getDatabaseConnection();
        }
    }

    /**
     */
    public function deleteExtraSymbol($name) {
		$filename = str_replace(array('../', '..\\', '/'), '', $name);
        $filepath = $this->config->getSystemValue('datadirectory').'/gpxedit/symbols/'.$filename;
        if (file_exists($filepath)){
            unlink($filepath);
        }
        return new DataResponse(
            [
                'data' =>
                [
                    'name' => $filename,
                    'message' => 'Deleted'
                ],
                'status' => 'success'
            ]
        );
    }

    /**
     */
    public function uploadExtraSymbol($addExtraSymbolName) {
		$newSymbol = $this->request->getUploadedFile('uploadsymbol');
		$filename = str_replace(array('../', '..\\', '/'), '', $addExtraSymbolName);
        if (!endswith($newSymbol['name'], '.png')){
            return new DataResponse(
                [
                    'data' =>
                    [
                        'message' => 'File has to be a png'
                    ],
                    'status' => 'fail'
                ],
                Http::STATUS_UNPROCESSABLE_ENTITY
            );
        }
        if (empty($newSymbol)) {
            return new DataResponse(
                [
                    'data' => [
                        'message' => 'No file uploaded'
                    ]
                ],
                Http::STATUS_UNPROCESSABLE_ENTITY
            );
        }
        if(!empty($newSymbol)) {
			$filepath = $this->config->getSystemValue('datadirectory').'/gpxedit/symbols/'.$filename.'.png';
	        $content = file_get_contents($newSymbol['tmp_name']);
            file_put_contents($filepath, $content);
        }
        return new DataResponse(
            [
                'data' =>
                [
                    'name' => $filename.'.png',
                    'message' => 'Saved'
                ],
                'status' => 'success'
            ]
        );
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     * @PublicPage
     */
    public function getExtraSymbol() {
        $filename = str_replace(array('../', '..\\', '/'), '', $_GET['name']);
        $filepath = $this->config->getSystemValue('datadirectory').'/gpxedit/symbols/'.$filename;
        $filecontent = file_get_contents($filepath);
        $response = new DataDisplayResponse(
            $filecontent, \OCP\AppFramework\Http::STATUS_OK, Array('Content-type'=>'image/png')
        );
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        return $response;
    }

    /**
     * Add one tile server to the DB for current user
     * @NoAdminRequired
     */
    public function addTileServer($servername, $serverurl, $type) {
        // first we check it does not already exist
        $sqlts = 'SELECT servername FROM *PREFIX*gpxedit_tile_servers ';
        $sqlts .= 'WHERE '.$this->dbdblquotes.'user'.$this->dbdblquotes.'=\''.$this->userId.'\' ';
        $sqlts .= 'AND servername='.$this->db_quote_escape_string($servername).' AND type='.$this->db_quote_escape_string($type).' ';
        $req = $this->dbconnection->prepare($sqlts);
        $req->execute();
        $ts = null;
        while ($row = $req->fetch()){
            $ts = $row['servername'];
            break;
        }
        $req->closeCursor();

        // then if not, we insert it
        if ($ts === null){
            $sql = 'INSERT INTO *PREFIX*gpxedit_tile_servers';
            $sql .= ' ('.$this->dbdblquotes.'user'.$this->dbdblquotes.', type, servername, url) ';
            $sql .= 'VALUES (\''.$this->userId.'\',';
            $sql .= ''.$this->db_quote_escape_string($type).',';
            $sql .= ''.$this->db_quote_escape_string($servername).',';
            $sql .= ''.$this->db_quote_escape_string($serverurl).');';
            $req = $this->dbconnection->prepare($sql);
            $req->execute();
            $req->closeCursor();
            $ok = 1;
        }
        else{
            $ok = 0;
        }

        $response = new DataResponse(
            [
                'done'=>$ok
            ]
        );
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        return $response;
    }

    /**
     * Delete one tile server entry from DB for current user
     * @NoAdminRequired
     */
    public function deleteTileServer($servername, $type) {
        $sqldel = 'DELETE FROM *PREFIX*gpxedit_tile_servers ';
        $sqldel .= 'WHERE '.$this->dbdblquotes.'user'.$this->dbdblquotes.'='.$this->db_quote_escape_string($this->userId).' AND servername=';
        $sqldel .= $this->db_quote_escape_string($servername).' AND type='.$this->db_quote_escape_string($type).';';
        $req = $this->dbconnection->prepare($sqldel);
        $req->execute();
        $req->closeCursor();

        $response = new DataResponse(
            [
                'done'=>1
            ]
        );
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        return $response;
    }

    /**
     * Save options values to the DB for current user
     * @NoAdminRequired
     */
    public function saveOptionsValues($optionsValues) {
        // first we check if user already has options values in DB
        $sqlts = 'SELECT jsonvalues FROM *PREFIX*gpxedit_options_values ';
        $sqlts .= 'WHERE '.$this->dbdblquotes.'user'.$this->dbdblquotes.'=\''.$this->userId.'\' ';
        $req = $this->dbconnection->prepare($sqlts);
        $req->execute();
        $check = null;
        while ($row = $req->fetch()){
            $check = $row['jsonvalues'];
            break;
        }
        $req->closeCursor();

        // if nothing is there, we insert
        if ($check === null){
            $sql = 'INSERT INTO *PREFIX*gpxedit_options_values';
            $sql .= ' ('.$this->dbdblquotes.'user'.$this->dbdblquotes.', jsonvalues) ';
            $sql .= 'VALUES (\''.$this->userId.'\',';
            $sql .= '\''.$optionsValues.'\');';
            $req = $this->dbconnection->prepare($sql);
            $req->execute();
            $req->closeCursor();
        }
        // else we update the values
        else{
            $sqlupd = 'UPDATE *PREFIX*gpxedit_options_values ';
            $sqlupd .= 'SET jsonvalues=\''.$optionsValues.'\' ';
            $sqlupd .= 'WHERE '.$this->dbdblquotes.'user'.$this->dbdblquotes.'=\''.$this->userId.'\' ; ';
            $req = $this->dbconnection->prepare($sqlupd);
            $req->execute();
            $req->closeCursor();
        }

        $response = new DataResponse(
            [
                'done'=>true
            ]
        );
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        return $response;
    }

    /**
     * get options values to the DB for current user
     * @NoAdminRequired
     */
    public function getOptionsValues($optionsValues) {
        $sqlov = 'SELECT jsonvalues FROM *PREFIX*gpxedit_options_values ';
        $sqlov .= 'WHERE '.$this->dbdblquotes.'user'.$this->dbdblquotes.'='.$this->db_quote_escape_string($this->userId).' ;';
        $req = $this->dbconnection->prepare($sqlov);
        $req->execute();
        $ov = '{}';
        while ($row = $req->fetch()){
            $ov = $row["jsonvalues"];
        }
        $req->closeCursor();

        $response = new DataResponse(
            [
                'values'=>$ov
            ]
        );
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        return $response;
    }

    /*
     * quote and choose string escape function depending on database used
     */
    private function db_quote_escape_string($str){
        return $this->dbconnection->quote($str);
    }

}
