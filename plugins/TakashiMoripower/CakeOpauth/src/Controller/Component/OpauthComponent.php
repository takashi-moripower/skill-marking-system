<?php

namespace TakashiMoripower\CakeOpauth\Controller\Component;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Routing\Router;

use TakashiMoripower\CakeOpauth\Controller\AuthController;

class OpauthComponent extends Component{
	public function call( $strategy , $callback_url ){
		$this->_controller = $this->_registry->getController();
		
		if( is_array( $callback_url )){
			$callback_url = Router::url( $callback_url );
		}
		$key = AuthController::$session_key;
		$this->request->session()->write("{$key}.callback_url" , $callback_url );
		
		return $this->_controller->redirect(['plugin'=>'TakashiMoripower/CakeOpauth' , 'controller'=>'auth', 'action'=>'auth' , $strategy ]);
	}
}