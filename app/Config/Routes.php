<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');

$routes->group('api', function (RouteCollection $routes) {

    $routes->group('v1', function (RouteCollection $routes) {

        $routes->resource('clientes', [
            'controller' => 'Api\V1\ClienteController'
        ]);

    });

});