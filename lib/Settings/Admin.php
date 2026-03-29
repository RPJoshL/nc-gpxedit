<?php
namespace OCA\GpxEdit\Settings;

use OCA\GpxEdit\Controller\MapboxController;
use OCA\GpxEdit\Helper\Helper;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IAppConfig;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\IURLGenerator;

class Admin implements ISettings {

    private string $dataDirPath;

	public function __construct(
		private IAppConfig $appConfig,
        private IConfig $config,
        private IURLGenerator $urlGenerator
	) {
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
        $extraSymbolList = Array();
	    foreach(Helper::globRecursive($this->dataDirPath.'/symbols', '*.png', False) as $symbolfile){
            $filename = basename($symbolfile);
            array_push($extraSymbolList, Array('smallname'=>str_replace('.png', '', $filename), 'name'=>$filename));
        }

        // Only show the first 4 characters of the API key for security reasons
        $adminMapboxApiKey = $this->appConfig->getAppValueString(MapboxController::API_KEY_CONFIG, '');
        if (strlen($adminMapboxApiKey) > 4) {
            $adminMapboxApiKey = substr($adminMapboxApiKey, 0, 4) . str_repeat('*', strlen($adminMapboxApiKey) - 4);
        }

        $parameters = [
            'extraSymbolList' => $extraSymbolList,
            'uploadPath' => $uploadPath,
            'saveMapboxApiKeyPath' => $this->urlGenerator->linkToRoute('gpxedit.utils.saveMapboxApiKey'),
            'adminMapboxApiKey' => $adminMapboxApiKey,
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
	    foreach(Helper::globRecursive($this->dataDirPath.'/symbols', '*.png', False) as $symbolfile){
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
