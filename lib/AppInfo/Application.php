<?php
/**
 * ownCloud - gpxedit
 *
 *
 * @author
 *
 * @copyright
 */

namespace OCA\GpxEdit\AppInfo;

use OCP\AppFramework\App;

use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Collaboration\Resources\LoadAdditionalScriptsEvent;
use OCP\EventDispatcher\IEventDispatcher;

/**
 * Class Application
 *
 * @package OCA\GpxEdit\AppInfo
 */
class Application extends App implements IBootstrap {

    public const APP_ID = 'gpxedit';

    /**
     * Constructor
     *
     * @param array $urlParams
     */
    public function __construct(array $urlParams = []) {
        parent::__construct(self::APP_ID, $urlParams);

        $eventDispatcher = $this->getContainer()->get(IEventDispatcher::class);
        $eventDispatcher->addListener(LoadAdditionalScriptsEvent::class, function() {
            \OCP\Util::addScript(self::APP_ID, 'filetypes');
            \OCP\Util::addStyle(self::APP_ID, 'style');
        });
	}

	public function register(IRegistrationContext $context): void {}

	public function boot(IBootContext $context): void {
	}

}

