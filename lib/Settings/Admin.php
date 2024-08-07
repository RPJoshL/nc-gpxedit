<?php
namespace OCA\GpxEdit\Settings;

use bantu\IniGetWrapper\IniGetWrapper;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IL10N;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\Util;
use OCP\IURLGenerator;

use function OCA\GpxEdit\Helper\globRecursive;

class Admin implements ISettings {

    /** @var IniGetWrapper */
    private $iniWrapper;

    /** @var IRequest */
    private $request;
    private $config;
    private $dataDirPath;
	private $urlGenerator;
	private $l;

    public function __construct(
                        IniGetWrapper $iniWrapper,
                        IL10N $l,
                        IRequest $request,
                        IConfig $config,
                        IURLGenerator $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
        $this->iniWrapper = $iniWrapper;
        $this->request = $request;
        $this->l = $l;
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
        $uploadPath = $this->urlGenerator->linkToRoute('gpxedit.utils.uploadExtraSymbol');
        //$extraSymbolList = Array(Array('name'=>'plop', 'url'=>'huhu'), Array('name'=>'lll', 'url'=>'uuu'));
        $extraSymbolList = Array();
	    foreach(globRecursive($this->dataDirPath.'/symbols', '*.png', False) as $symbolfile){
            $filename = basename($symbolfile);
            array_push($extraSymbolList, Array('smallname'=>str_replace('.png', '', $filename), 'name'=>$filename));
        }    

        $parameters = [
            'extraSymbolList' => $extraSymbolList,
            'uploadPath' => $uploadPath
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

    /**
     * @return TemplateResponse
     * for ownCloud 10+
     */
    public function getPanel() {
        $uploadPath = $this->urlGenerator->linkToRoute('gpxedit.utils.uploadExtraSymbol');
        //$extraSymbolList = Array(Array('name'=>'plop', 'url'=>'huhu'), Array('name'=>'lll', 'url'=>'uuu'));
        $extraSymbolList = Array();
	    foreach(globRecursive($this->dataDirPath.'/symbols', '*.png', False) as $symbolfile){
            $filename = basename($symbolfile);
            array_push($extraSymbolList, Array('smallname'=>str_replace('.png', '', $filename), 'name'=>$filename));
        }    

        $parameters = [
            'extraSymbolList' => $extraSymbolList,
            'uploadPath' => $uploadPath
        ];

        return new TemplateResponse('gpxedit', 'admin', $parameters, '');
    }

    /**
     * @return string the section ID, e.g. 'sharing'
     * for ownCloud 10+
     */
    public function getSectionID() {
        return 'additional';
    }

}
