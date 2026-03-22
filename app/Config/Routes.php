<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/dashboard', 'DashboardController::index');

    // consultas
    $routes->get('/consultas', 'QueriesController::index');
    $routes->get('/consultas/nova', 'QueriesController::new');
    $routes->post('/consultas/create', 'QueriesController::create');
    $routes->get('/consultas/(:num)', 'QueriesController::show/$1');
    $routes->get('/consultas/editar/(:num)', 'QueriesController::edit/$1');
    $routes->post('/consultas/update/(:num)', 'QueriesController::update/$1');
    $routes->post('/consultas/delete/(:num)', 'QueriesController::delete/$1');

    // favoritos
    $routes->post('/favoritos/toggle/(:num)', 'FavoritesController::toggle/$1');
});
