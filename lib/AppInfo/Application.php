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

use OC\App\AppManager;
use OCP\IContainer;

use Psr\Container\ContainerInterface;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;

use OCA\GpxEdit\Controller\PageController;
use OCA\GpxEdit\Controller\ComparisonController;
use OCA\GpxEdit\Controller\UtilsController;
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

	public function register(IRegistrationContext $context): void {

        $context->registerService(
            'PageController', function (ContainerInterface $c) {
                return new PageController(
                    $c->get('AppName'),
                    $c->get('Request'),
                    $c->get('UserId'),
                    $c->get('ServerContainer')->getUserFolder($c->get('UserId')),
                    $c->get('ServerContainer')->getConfig(),
                    $c->get(AppManager::class)
                );
            }
        );

        $context->registerService(
            'UtilsController', function (ContainerInterface $c) {
                return new UtilsController(
                    $c->get('AppName'),
                    $c->get('Request'),
                    $c->get('UserId'),
                    //$c->getServer()->getUserFolder($c->query('UserId')),
                    //$c->query('OCP\IConfig'),
                    $c->get('ServerContainer')->getUserFolder($c->get('UserId')),
                    $c->get('ServerContainer')->getConfig(),
                    $c->get(AppManager::class)
                );
            }
        );
	}

	public function boot(IBootContext $context): void {
	}

}

