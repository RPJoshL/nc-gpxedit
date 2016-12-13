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

use \OC_App;

use OCP\IURLGenerator;
use OCP\IConfig;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\RedirectResponse;

use OCP\AppFramework\Http\ContentSecurityPolicy;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Controller;

function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

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

class PageController extends Controller {

    private $userId;
    private $userfolder;
    private $config;
    private $appVersion;
    private $userAbsoluteDataPath;
    private $shareManager;
    private $dbconnection;
    private $dbtype;
    private $dbdblquotes;
    private $appPath;

    public function __construct($AppName, IRequest $request, $UserId,
                                $userfolder, $config, $shareManager){
        parent::__construct($AppName, $request);
        $this->appVersion = $config->getAppValue('gpxedit', 'installed_version');
        $this->appPath = \OC_App::getAppPath('gpxedit');
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
        //$this->shareManager = \OC::$server->getShareManager();
        $this->shareManager = $shareManager;
    }

    /**
     * Welcome page.
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index() {
        $userFolder = \OC::$server->getUserFolder();
        $userfolder_path = $userFolder->getPath();

        $tss = $this->getUserTileServers();

        // extra symbols
        $dataDirPath = $this->config->getSystemValue('datadirectory').'/gpxedit';
        $extraSymbolList = Array();
        if (is_dir($dataDirPath.'/symbols')){
            foreach(globRecursive($dataDirPath.'/symbols', '*.png', False) as $symbolfile){
                $filename = basename($symbolfile);
                array_push($extraSymbolList, Array('smallname'=>str_replace('.png', '', $filename), 'name'=>$filename));
            }
        }

        // PARAMS to view

        $params = [
            'username'=>$this->userId,
			'tileservers'=>$tss,
            'extrasymbols'=>$extraSymbolList,
            'gpxedit_version'=>$this->appVersion
        ];
        $response = new TemplateResponse('gpxedit', 'main', $params);
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedChildSrcDomain('*')
            ->addAllowedObjectDomain('*')
            ->addAllowedScriptDomain('*')
            //->allowEvalScript('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        return $response;
    }


    /**
     * convert the given file (csv or kml) to gpx and return its content
     */
    public function toGpx($file){
        $gpsbabel_path = getProgramPath('gpsbabel');
        $data_folder = $this->userAbsoluteDataPath;
        $tempdir = $data_folder.'/../cache/'.rand();
        mkdir($tempdir);

        $filename = $file->getName();
        $filecontent = $file->getContent();
        $file_clear_path = $tempdir.'/'.$filename;
        $gpx_target_clear_path = $tempdir.'/'.$filename.'.gpx';
        file_put_contents($file_clear_path, $filecontent);

        if (endswith($file->getName(), '.KML') or endswith($file->getName(), '.kml')){
            $fmt = 'kml';
        }
        else if (endswith($file->getName(), '.csv') or endswith($file->getName(), '.CSV')){
            $fmt = 'unicsv';
        }
        else if (endswith($file->getName(), '.jpg') or endswith($file->getName(), '.JPG')){
            $fmt = 'exif';
        }
        $args = Array('-i', $fmt, '-f', $file_clear_path, '-o',
            'gpx', '-F', $gpx_target_clear_path);
        $cmdparams = '';
        foreach($args as $arg){
            $shella = escapeshellarg($arg);
            $cmdparams .= " $shella";
        }
        exec(
            escapeshellcmd(
                $gpsbabel_path.' '.$cmdparams
            ),
            $output, $returnvar
        );
        if (file_exists($gpx_target_clear_path)){
            $gpx_clear_content = file_get_contents($gpx_target_clear_path);
        }
        else{
            $gpx_clear_content = '';
        }

        delTree($tempdir);

        return $gpx_clear_content;
    }

    /**
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getgpx($path) {
        $userFolder = \OC::$server->getUserFolder();
        $cleanpath = str_replace(array('../', '..\\'), '',  $path);
        $gpxContent = '';
        if ($userFolder->nodeExists($cleanpath)){
            $file = $userFolder->get($cleanpath);
            if ($file->getType() === \OCP\Files\FileInfo::TYPE_FILE){
                if (endswith($file->getName(), '.GPX') or endswith($file->getName(), '.gpx')){
                    $gpxContent = $file->getContent();
                }
                else if (getProgramPath('gpsbabel') !== null and
                    (endswith($file->getName(), '.KML') or endswith($file->getName(), '.kml') or
                    endswith($file->getName(), '.JPG') or endswith($file->getName(), '.jpg') or
                    endswith($file->getName(), '.CSV') or endswith($file->getName(), '.csv'))
                ){
                    $gpxContent = $this->toGpx($file);
                }
            }
            else{
                $file = null;
            }
        }

        $response = new DataResponse(
            [
                'gpx'=>$gpxContent
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
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function savegpx($path, $content) {
        $userFolder = \OC::$server->getUserFolder();
        $cleanpath = str_replace(array('../', '..\\'), '',  $path);
        $status = false;
        if (endswith($cleanpath, '.GPX') or endswith($cleanpath, '.gpx')){
            if ($userFolder->nodeExists($cleanpath)){
                $file = $userFolder->get($cleanpath);
                if ($file->getType() === \OCP\Files\FileInfo::TYPE_FILE and
                    $file->isUpdateable()){
                    $file->putContent($content);
                    $status = true;
                }
                else{
                    $status = 'fiw';
                }
            }
            else{
                $dirpath = dirname($cleanpath);
                $newFileName = basename($cleanpath);
                if ($userFolder->nodeExists($dirpath)){
                    $dir = $userFolder->get($dirpath);
                    if ($dir->getType() === \OCP\Files\FileInfo::TYPE_FOLDER and
                        $dir->isCreatable()){
                        $dir->newFile($newFileName);
                        $dir->get($newFileName)->putContent($content);
                        $status = true;
                    }
                    else{
                        $status = 'fw';
                    }
                }
                else{
                    $status = 'fu';
                }
            }
        }
        else{
            $status = 'bfn';
        }

        $response = new DataResponse(
            [
                'status'=>$status
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
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getdircontent($dir) {
        $userFolder = \OC::$server->getUserFolder();
        $userfolder_path = $userFolder->getPath();
        $gpsbabelpath = getProgramPath('gpsbabel');
        $responseTxt = '<ul class="jqueryFileTree">';

        //error_log('DIR : '.$dir);

        if ($userFolder->nodeExists($dir)){
            $direlem = $userFolder->get($dir);
            if ($direlem->getType() === \OCP\Files\FileInfo::TYPE_FOLDER){
                foreach($direlem->getDirectoryListing() as $elem){
                    $elempath = str_replace($userfolder_path, '', $elem->getPath());
                    if ($elem->getType() === \OCP\Files\FileInfo::TYPE_FOLDER){
                        $responseTxt .= '<li class="directory collapsed"><a href="#" rel="'.$elempath.'">'.$elem->getName().'</a></li>';
                    }
                    else if ($elem->getType() === \OCP\Files\FileInfo::TYPE_FILE){
                       if (endswith($elempath, '.gpx') or endswith($elempath, '.GPX')){
                           $responseTxt .= '<li class="gpx ext_gpx"><a href="#" rel="'.$elempath.'">'.$elem->getName().'</a></li>';
                       }
                       else if ($gpsbabelpath !== null and
                       (endswith($elempath, '.csv') or endswith($elempath, '.CSV'))){
                           $responseTxt .= '<li class="csv ext_csv"><a href="#" rel="'.$elempath.'">'.$elem->getName().'</a></li>';
                       }
                       else if ($gpsbabelpath !== null and
                       (endswith($elempath, '.kml') or endswith($elempath, '.KML'))){
                           $responseTxt .= '<li class="kml ext_kml"><a href="#" rel="'.$elempath.'">'.$elem->getName().'</a></li>';
                       }
                       else if ($gpsbabelpath !== null and
                       (endswith($elempath, '.jpg') or endswith($elempath, '.JPG'))){
                           $responseTxt .= '<li class="jpg ext_jpg"><a href="#" rel="'.$elempath.'">'.$elem->getName().'</a></li>';
                       }
                    }
                }
            }
        }

        $responseTxt .= '</ul>';

        $response = new Response();
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        //error_log($responseTxt);
        echo $responseTxt;
        return $response;
    }

    /**
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getdircontentdir($dir) {
        $userFolder = \OC::$server->getUserFolder();
        $userfolder_path = $userFolder->getPath();
        $responseTxt = '<ul class="jqueryFileTree">';

        if ($userFolder->nodeExists($dir)){
            $direlem = $userFolder->get($dir);
            if ($direlem->getType() === \OCP\Files\FileInfo::TYPE_FOLDER){
                foreach($direlem->getDirectoryListing() as $elem){
                    $elempath = str_replace($userfolder_path, '', $elem->getPath());
                    if ($elem->getType() === \OCP\Files\FileInfo::TYPE_FOLDER){
                        $responseTxt .= '<li class="directory collapsed"><a href="#" rel="'.$elempath.'">'.$elem->getName().'</a></li>';
                    }
                    //else if ($elem->getType() === \OCP\Files\FileInfo::TYPE_FILE and
                    //(endswith($elempath, '.gpx') or endswith($elempath, '.GPX'))){
                    //    $responseTxt .= '<li class="file ext_gpx"><a href="#" rel="'.$elempath.'">'.$elem->getName().'</a></li>';
                    //}
                }
            }
        }

        $responseTxt .= '</ul>';

        $response = new Response();
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*')
            ->addAllowedMediaDomain('*')
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        //error_log($responseTxt);
        echo $responseTxt;
        return $response;
    }

    private function getUserTileServers(){
        // custom tile servers management
        $sqlts = 'SELECT servername, url FROM *PREFIX*gpxedit_tile_servers ';
        $sqlts .= 'WHERE '.$this->dbdblquotes.'user'.$this->dbdblquotes.'=\''.$this->userId.'\';';
        $req = $this->dbconnection->prepare($sqlts);
        $req->execute();
        $tss = Array();
        while ($row = $req->fetch()){
            $tss[$row["servername"]] = $row["url"];
        }
        $req->closeCursor();
        return $tss;
    }

}
