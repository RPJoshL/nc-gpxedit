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
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Controller;

require_once(__DIR__ . '/../Helper/Functions.php');
use function OCA\GpxEdit\Helper\globRecursive;
use function OCA\GpxEdit\Helper\getProgramPath;
use function OCA\GpxEdit\Helper\endswith;

require_once('Conversion.php');

function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

class PageController extends Controller {

    private $userId;
    private $userfolder;
    private $config;
    private $appVersion;
    private $userAbsoluteDataPath;
    private $dbconnection;
    private $dbtype;
    private $dbdblquotes;

    public function __construct($AppName, IRequest $request, $UserId,
                                $userfolder, $config, IAppManager $appManager){
        parent::__construct($AppName, $request);
        $this->appVersion = $config->getAppValue('gpxedit', 'installed_version');
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
     * Welcome page.
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index() {
        $userFolder = \OC::$server->getUserFolder();
        $userfolder_path = $userFolder->getPath();

        $tss = $this->getUserTileServers('tile');
        $oss = $this->getUserTileServers('overlay');
        $tssw = $this->getUserTileServers('tilewms');
        $ossw = $this->getUserTileServers('overlaywms');

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

        require_once('TileServers.php');
        $params = [
            'username'=>$this->userId,
            'basetileservers'=>$baseTileServers,
            'usertileservers'=>$tss,
            'useroverlayservers'=>$oss,
            'usertileserverswms'=>$tssw,
            'useroverlayserverswms'=>$ossw,
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
            ->allowEvalScript(true)
            ->addAllowedConnectDomain('*');
        $response->setContentSecurityPolicy($csp);
        return $response;
    }


    /**
     * convert the given file (csv or kml) to gpx and return its content
     */
    private function toGpx($file){
        $gpxContent = '';
        $gpsbabel_path = getProgramPath('gpsbabel');
        $data_folder = $this->userAbsoluteDataPath;
        $tempdir = $data_folder.'/../cache/'.rand();
        mkdir($tempdir);

        $filename = $file->getName();
        $filecontent = $file->getContent();
        $file_clear_path = $tempdir.'/'.$filename;
        file_put_contents($file_clear_path, $filecontent);

        if (endswith($file->getName(), '.KML') or endswith($file->getName(), '.kml')){
            $gpxContent = kmlToGpx($file_clear_path);
        }
        else if (endswith($file->getName(), '.csv') or endswith($file->getName(), '.CSV')){
            $gpxContent = unicsvToGpx($file_clear_path);
        }
        else if (endswith($file->getName(), '.jpg') or endswith($file->getName(), '.JPG')){
            $gpxContent = jpgToGpx($file_clear_path, $filename);
        }

        delTree($tempdir);

        return $gpxContent;
    }

    /**
     * @NoAdminRequired
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
     * @NoAdminRequired
     */
    public function getfoldergpxs($path, $type) {
        $userFolder = \OC::$server->getUserFolder();
        $cleanpath = str_replace(array('../', '..\\'), '',  $path);
        $gpxs = Array();
        if ($userFolder->nodeExists($cleanpath)){
            $folder = $userFolder->get($cleanpath);
            if ($folder->getType() === \OCP\Files\FileInfo::TYPE_FOLDER){
                foreach ($folder->getDirectoryListing() as $file) {
                    if ($file->getType() === \OCP\Files\FileInfo::TYPE_FILE) {
                        if (    ($type === 'all' or $type === '.gpx')
                            and (endswith($file->getName(), '.GPX') or endswith($file->getName(), '.gpx'))
                        ){
                            $gpxContent = $file->getContent();
                            array_push($gpxs, $gpxContent);
                        }
                        else if (getProgramPath('gpsbabel') !== null and
                            (
                                (    ($type === 'all' or $type === '.kml')
                                 and (endswith($file->getName(), '.KML') or endswith($file->getName(), '.kml'))
                                )
                                or
                                (    ($type === 'all' or $type === '.jpg')
                                 and (endswith($file->getName(), '.JPG') or endswith($file->getName(), '.jpg'))
                                )
                                or
                                (    ($type === 'all' or $type === '.csv')
                                 and (endswith($file->getName(), '.CSV') or endswith($file->getName(), '.csv'))
                                )
                            )
                        ){
                            $gpxContent = $this->toGpx($file);
                            array_push($gpxs, $gpxContent);
                        }
                    }
                }
            }
        }

        $response = new DataResponse(
            [
                'gpxs'=>$gpxs
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
     * @NoAdminRequired
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

    /*
     * quote and choose string escape function depending on database used
     */
    private function db_quote_escape_string($str){
        return $this->dbconnection->quote($str);
    }

    private function getUserTileServers($type){
        // custom tile servers management
        $sqlts = 'SELECT servername, type, url, layers, version, format, opacity, transparent, minzoom, maxzoom, attribution FROM *PREFIX*gpxedit_tile_servers ';
        $sqlts .= 'WHERE '.$this->dbdblquotes.'user'.$this->dbdblquotes.'='.$this->db_quote_escape_string($this->userId).' ';
        $sqlts .= 'AND type='.$this->db_quote_escape_string($type).';';
        $req = $this->dbconnection->prepare($sqlts);
        $req->execute();
        $tss = Array();
        while ($row = $req->fetch()){
            $tss[$row["servername"]] = Array();
            foreach (Array('servername', 'type', 'url', 'layers', 'version', 'format', 'opacity', 'transparent', 'minzoom', 'maxzoom', 'attribution') as $field) {
                $tss[$row['servername']][$field] = $row[$field];
            }
        }
        $req->closeCursor();
        return $tss;
    }

}
