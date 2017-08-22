<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(
    'TakashiMoripower/CakeOpauth',
    ['path' => '/opauth'],
    function (RouteBuilder $routes) {

		$routes->connect('/auth/callback' , ['controller'=>'Auth' , 'action'=>'callback']);
		$routes->connect('/auth/*' , ['controller'=>'Auth','action'=>'auth'] );

		$routes->fallbacks('DashedRoute');
    }
);
