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

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\GpxEdit\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'page#getgpx', 'url' => '/getgpx', 'verb' => 'POST'],
        ['name' => 'page#getfoldergpxs', 'url' => '/getfoldergpxs', 'verb' => 'POST'],
        ['name' => 'page#savegpx', 'url' => '/savegpx', 'verb' => 'POST'],
        ['name' => 'utils#addTileServer', 'url' => '/addTileServer', 'verb' => 'POST'],
        ['name' => 'utils#deleteTileServer', 'url' => '/deleteTileServer', 'verb' => 'POST'],
        ['name' => 'utils#getOptionsValues', 'url' => '/getOptionsValues', 'verb' => 'POST'],
        ['name' => 'utils#saveOptionsValues', 'url' => '/saveOptionsValues', 'verb' => 'POST'],
        ['name' => 'utils#getExtraSymbol', 'url' => '/getExtraSymbol', 'verb' => 'GET'],
        ['name' => 'utils#uploadExtraSymbol', 'url' => '/uploadExtraSymbol', 'verb' => 'POST'],
        ['name' => 'utils#deleteExtraSymbol', 'url' => '/deleteExtraSymbol', 'verb' => 'POST'],
    ]
];
