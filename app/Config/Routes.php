<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// Groupe de routes avec versionning
$routes->group('/api/v1', static function ($routes) {
    // Authentification
    $routes->group('auth', static function ($routes) {
        $routes->post('login', 'AuthController::login');    
    });    

    // Client (['filter' => 'auth'] )
    $routes->group('client', static function($routes){
        $routes->get('', 'ClientController::list');
        $routes->get('(:num)', 'ClientController::read/$1');
        $routes->post('', 'ClientController::create');
        $routes->put('(:num)', 'ClientController::update/$1');
        $routes->get('delete/(:num)', 'ClientController::delete/$1');
        $routes->get('activate/(:num)', 'ClientController::activateClient/$1');
        $routes->get('deactivate/(:num)', 'ClientController::deactivateClient/$1');
        $routes->get('recoveredClients', 'ClientController::listRecovered');
        $routes->get('reactivateClients/(:num)', 'ClientController::reactivateClients/$1');
    });

    // User 
    $routes->group('user', static function($routes){
        $routes->get('', 'UserController::list');
        $routes->get('(:num)', 'UserController::read/$1');
        $routes->post('', 'UserController::create');    
        $routes->put('(:num)', 'UserController::update/$1');
        $routes->get('delete/(:num)', 'UserController::delete/$1');
        $routes->get('activate/(:num)', 'UserController::activateUser/$1');
        $routes->get('deactivate/(:num)', 'UserController::deactivateUser/$1');
        $routes->get('roles', 'UserController::getRoles');
        $routes->get('userRoles/(:num)', 'UserController::getUserRoles/$1');
        $routes->put('editRolesUser/(:num)', 'UserController::editRolesUser/$1');
        $routes->get('recoveredUsers', 'UserController::listRecovered');
        $routes->get('reactivateUsers/(:num)', 'UserController::reactivateUsers/$1');
    });

    // Administrator
    $routes->group('administrator', static function($routes){
        $routes->get('', 'UserController::listAdministrators');
        $routes->get('(:num)', 'UserController::read/$1');
        $routes->put('(:num)', 'UserController::update/$1');
        $routes->get('activate/(:num)', 'UserController::activateUser/$1');
        $routes->get('deactivate/(:num)', 'UserController::deactivateUser/$1');
        $routes->post('createAdministrator', 'UserController::createAdministrator');
        $routes->get('delete/(:num)', 'UserController::delete/$1');
        $routes->get('recoveredAdministrators', 'UserController::listRecoverdAdministrators');
    });
});



