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

use OCA\GpxEdit\AppInfo\Application;
use OCA\GpxEdit\Helper\Helper;
use OCP\App\IAppManager;

use OCP\AppFramework\Http\Attribute\PasswordConfirmationRequired;
use OCP\AppFramework\Http\Attribute\PublicPage;
use OCP\AppFramework\Http\TextPlainResponse;
use OCP\Files\IRootFolder;
use OCP\IAppConfig;
use OCP\IDBConnection;
use OCP\IConfig;

use OCP\AppFramework\Http;

use OCP\AppFramework\Http\ContentSecurityPolicy;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Controller;

class UtilsController extends Controller {

    private string $userAbsoluteDataPath;
    private string $dbtype;
    private string $dbDoubleQuotes;

    public function __construct(
        string $appName, 
        IRequest $request, 
        private ?string $userId,
        private IAppConfig $appConfig, 
        private IRootFolder $rootFolder,
        private IConfig $config,
        private IDBConnection $dbconnection,
        IAppManager $appManager
    ){
        parent::__construct($appName, $request);

        $this->dbtype = $config->getSystemValue('dbtype');
		if ($this->dbtype === 'pgsql') {
			$this->dbDoubleQuotes = '"';
		} else {
			$this->dbDoubleQuotes = '';
		}

        if ($this->userId !== null){
            $userFolder = $this->rootFolder->getUserFolder($this->userId);
            $this->userAbsoluteDataPath = $this->config->getSystemValue('datadirectory').rtrim($userFolder->getFullPath(''), '/');

            // make cache if it does not exist
            $cachedirpath = $this->userAbsoluteDataPath.'/../cache';
            if (! is_dir($cachedirpath)){
                mkdir($cachedirpath);
            }
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
        if (!Helper::endswith($newSymbol['name'], '.png')){
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

    #[PasswordConfirmationRequired]
    public function saveMapboxApiKey($mapboxApiKey) {
        $this->appConfig->setValueString(Application::APP_ID, MapboxController::API_KEY_CONFIG, $mapboxApiKey, false, true);
        return new TextPlainResponse('API key saved');
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
            $filecontent, Http::STATUS_OK, Array('Content-type'=>'image/png')
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
    public function addTileServer($servername, $serverurl, $type,
                    $layers, $version, $tformat, $opacity, $transparent,
                    $minzoom, $maxzoom, $attribution) {
        // first we check it does not already exist
        $sqlts = 'SELECT servername FROM *PREFIX*gpxedit_tile_servers ';
        $sqlts .= 'WHERE '.$this->dbDoubleQuotes.'user'.$this->dbDoubleQuotes.'=\''.$this->userId.'\' ';
        $sqlts .= 'AND servername='.$this->db_quote_escape_string($servername).' ';
        $sqlts .= 'AND type='.$this->db_quote_escape_string($type).' ';
        $req = $this->dbconnection->prepare($sqlts);
        $res = $req->execute();
        $ts = null;
        while ($row = $res->fetch()){
            $ts = $row['servername'];
            break;
        }
        $res->closeCursor();

        // then if not, we insert it
        if ($ts === null){
            $sql = 'INSERT INTO *PREFIX*gpxedit_tile_servers';
            $sql .= ' ('.$this->dbDoubleQuotes.'user'.$this->dbDoubleQuotes.', type, servername, url, layers, version, format, opacity, transparent, minzoom, maxzoom, attribution) ';
            $sql .= 'VALUES (\''.$this->userId.'\',';
            $sql .= $this->db_quote_escape_string($type).',';
            $sql .= $this->db_quote_escape_string($servername).',';
            $sql .= $this->db_quote_escape_string($serverurl).',';
            $sql .= $this->db_quote_escape_string($layers).',';
            $sql .= $this->db_quote_escape_string($version).',';
            $sql .= $this->db_quote_escape_string($tformat).',';
            $sql .= $this->db_quote_escape_string($opacity).',';
            $sql .= $this->db_quote_escape_string($transparent).',';
            $sql .= $this->db_quote_escape_string($minzoom).',';
            $sql .= $this->db_quote_escape_string($maxzoom).',';
            $sql .= $this->db_quote_escape_string($attribution).');';
            $req = $this->dbconnection->prepare($sql);
            $req->execute()->closeCursor();
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
        $sqldel .= 'WHERE '.$this->dbDoubleQuotes.'user'.$this->dbDoubleQuotes.'='.$this->db_quote_escape_string($this->userId).' AND servername=';
        $sqldel .= $this->db_quote_escape_string($servername).' AND type='.$this->db_quote_escape_string($type).';';
        $req = $this->dbconnection->prepare($sqldel);
        $req->execute()->closeCursor();

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
        $sqlts = 'SELECT jsonvalues FROM *PREFIX*gpxedit_options ';
        $sqlts .= 'WHERE '.$this->dbDoubleQuotes.'user'.$this->dbDoubleQuotes.'=\''.$this->userId.'\' ';
        $req = $this->dbconnection->prepare($sqlts);
        $res = $req->execute();
        $check = null;
        while ($row = $res->fetch()){
            $check = $row['jsonvalues'];
            break;
        }
        $res->closeCursor();

        // if nothing is there, we insert
        if ($check === null){
            $sql = 'INSERT INTO *PREFIX*gpxedit_options';
            $sql .= ' ('.$this->dbDoubleQuotes.'user'.$this->dbDoubleQuotes.', jsonvalues) ';
            $sql .= 'VALUES (\''.$this->userId.'\',';
            $sql .= '\''.$optionsValues.'\');';
            $req = $this->dbconnection->prepare($sql);
            $res = $req->execute();
            $res->closeCursor();
        }
        // else we update the values
        else{
            $sqlupd = 'UPDATE *PREFIX*gpxedit_options ';
            $sqlupd .= 'SET jsonvalues=\''.$optionsValues.'\' ';
            $sqlupd .= 'WHERE '.$this->dbDoubleQuotes.'user'.$this->dbDoubleQuotes.'=\''.$this->userId.'\' ; ';
            $req = $this->dbconnection->prepare($sqlupd);
            $res = $req->execute();
            $res->closeCursor();
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
        $sqlov = 'SELECT jsonvalues FROM *PREFIX*gpxedit_options ';
        $sqlov .= 'WHERE '.$this->dbDoubleQuotes.'user'.$this->dbDoubleQuotes.'='.$this->db_quote_escape_string($this->userId).' ;';
        $req = $this->dbconnection->prepare($sqlov);
        $res = $req->execute();
        $ov = '{}';
        while ($row = $res->fetch()){
            $ov = $row["jsonvalues"];
        }
        $res->closeCursor();

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
