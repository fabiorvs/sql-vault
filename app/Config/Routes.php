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

    // favoritos
    $routes->post('/favoritos/toggle/(:num)', 'FavoritesController::toggle/$1');
});
