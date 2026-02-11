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

        $routes->resource('propostas', [
            'controller' => 'Api\V1\PropostaController'
        ]);

        $routes->post('propostas/(:num)/submit', 'Api\V1\PropostaController::submit/$1');
        $routes->post('propostas/(:num)/approve', 'Api\V1\PropostaController::approve/$1');
        $routes->post('propostas/(:num)/reject', 'Api\V1\PropostaController::reject/$1');
        $routes->post('propostas/(:num)/cancel', 'Api\V1\PropostaController::cancel/$1');

        $routes->get('propostas/(:num)/auditoria', 'Api\V1\PropostaController::auditoria/$1');

    });

});