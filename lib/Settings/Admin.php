<?php
namespace OCA\GpxEdit\Settings;

use bantu\IniGetWrapper\IniGetWrapper;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\Util;

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

class Admin implements ISettings {

    /** @var IniGetWrapper */
    private $iniWrapper;

    /** @var IRequest */
    private $request;
    private $config;
    private $dataDirPath;

    public function __construct(IniGetWrapper $iniWrapper, IRequest $request, IConfig $config) {
        $this->iniWrapper = $iniWrapper;
        $this->request = $request;
        $this->config = $config;
        $this->dataDirPath = $this->config->getSystemValue('datadirectory').'/gpxedit';
        if (! is_dir($this->dataDirPath)){
            mkdir($this->dataDirPath);
        }
        if (! is_dir($this->dataDirPath.'/symbols')){
            mkdir($this->dataDirPath.'/symbols');
        }

    }

    /**
     * @return TemplateResponse
     */
    public function getForm() {
        //$extraSymbolList = Array(Array('name'=>'plop', 'url'=>'huhu'), Array('name'=>'lll', 'url'=>'uuu'));
        $extraSymbolList = Array();
	    foreach(globRecursive($this->dataDirPath.'/symbols', '*.png', False) as $symbolfile){
            $filename = basename($symbolfile);
            array_push($extraSymbolList, Array('name'=>str_replace('.png', '', $filename), 'url'=>$filename));
        }    

        $parameters = [
            'extraSymbolList' => $extraSymbolList
        ];

        return new TemplateResponse('gpxedit', 'admin', $parameters, '');
    }

    /**
     * @return string the section ID, e.g. 'sharing'
     */
    public function getSection() {
        return 'additional';
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the admin section. The forms are arranged in ascending order of the
     * priority values. It is required to return a value between 0 and 100.
     *
     * E.g.: 70
     */
    public function getPriority() {
        return 5;
    }

}
