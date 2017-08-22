<?php

namespace TakashiMoripower\CakeOpauth\Controller;

use TakashiMoripower\CakeOpauth\Controller\AppController;
use Cake\Routing\Router;
use Opauth;
use Cake\Core\Configure;

/**
 * Main Controller
 *
 * @property \Comvert\Model\Table\MainTable $Main
 */
class AuthController extends AppController {
	
	static $session_key = 'cakeopauth';

	public function auth($strategy) {

		$config = Configure::read('opauth');

		$config['path'] = Router::url($config['path']);
		$config['callback_url'] = Router::url($config['callback_url']);

		$key = self::$session_key;
		
		$session_url =  $this->request->session()->read("{$key}.callback_url");
		
		if ($session_url) {
			$config['callback_url'] = $session_url;
		}

		$this->Opauth = new Opauth($config);
	}

	public function callback() {
		$this->set('data', $this->request->session()->read('opauth'));
		$this->render("/Common/debug");
	}

}
