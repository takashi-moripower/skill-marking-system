<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(
    'TakashiMoripower/AclManager',
    ['path' => '/acl'],
    function (RouteBuilder $routes) {
		$routes->connect('/', ['controller' => 'Groups', 'action' => 'index']);
		$routes->connect('/:action', ['controller' => 'Groups']);
		$routes->connect('/:action/*', ['controller' => 'Groups']);
        $routes->fallbacks('DashedRoute');
    }
);
