<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {
    $routes->resource('clientes', [
        'controller' => 'Api\ClienteController'
    ]);
});