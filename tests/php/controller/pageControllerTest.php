<?php
/**
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\GpxEdit\Controller;

use \OCA\GpxEdit\AppInfo\Application;

//class PageControllerTest extends \PHPUnit_Framework_TestCase {
class PageControllerTest extends \PHPUnit\Framework\TestCase {

	private $appName;
	private $request;
	private $contacts;

	private $container;
	private $app;

	private $controller;

	public function setUp() {
		$this->appName = 'gpxedit';
		$this->request = $this->getMockBuilder('\OCP\IRequest')
			->disableOriginalConstructor()
			->getMock();
		$this->contacts = $this->getMockBuilder('OCP\Contacts\IManager')
			->disableOriginalConstructor()
			->getMock();

        $this->app = new Application();
        $this->container = $this->app->getContainer();
        $c = $this->container;

        // CREATE DUMMY USERS
        $c->getServer()->getUserManager()->createUser('testUser', 'T0T0T0');
        $c->getServer()->getUserManager()->createUser('testUser2', 'T0T0T0');

        $this->controller = new UtilsController(
            $this->appName,
            $this->request,
            'test',
            $c->query('ServerContainer')->getUserFolder('testUser'),
            $c->query('ServerContainer')->getConfig(),
            //$c->getServer()->getShareManager(),
            $c->getServer()->getAppManager()
        );
	}

	public function tearDown() {
        $user = $this->container->getServer()->getUserManager()->get('testUser');
        $user->delete();
        $user = $this->container->getServer()->getUserManager()->get('testUser2');
        $user->delete();
    }

	public function testSession() {
        $resp = $this->controller->addTileServer('superserver', 'http://plop.org', 'type',
                    'layers', 'version', 'tformat', '0.5', 'true',
                    '13', '16', 'attr');

        $data = $resp->getData();
        $done = $data['done'];

        $this->assertEquals($done, 1);
	}

}
