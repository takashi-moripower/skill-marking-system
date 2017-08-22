<?php

namespace TakashiMoripower\AclManager\View\Helper;

use Cake\View\Helper;
use Acl\Controller\Component\AclComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Utility\Hash;

/**
 * FlashHelper class to render flash messages.
 *
 */
class AclHelper extends Helper {

	public $helpers = ['Html'];

	/**
	 * Default config
	 *
	 * - class: List of classes to be applied to the div containing message
	 * - attributes: Additional attributes for the div containing message
	 *
	 * @var array
	 */
	protected $_defaultConfig = [
	];
	protected $_group = NULL;
	protected $_acl = NULL;
	
	protected $_cash = [];

	protected function _getGroup() {
		
		if ($this->_group) {
			return $this->_group;
		}
		if ($this->_group == "not found") {
			return NULL;
		}
		
		$group_id = $this->request->Session()->read('Auth.User.group_id');

		if (empty($group_id)) {
			$this->_group = "not found";
			return NULL;
		}

		$this->_group = \Cake\ORM\TableRegistry::get('Groups')->get($group_id);
		return $this->_group;
	}

	protected function _getAcl() {
		if (empty($this->_acl)) {
			$collection = new ComponentRegistry();
			$this->_acl = new AclComponent($collection);
		}
		return $this->_acl;
	}

	public function check($controller, $action) {
		$group = $this->_getGroup();
		$aco = 'App/' . $controller . '/' . $action;
		
		$cash_key =  "{$group->id}.{$aco}"  ;
		
		$cash = Hash::get( $this->_cash , $cash_key , 'undefined');
		
		if( $cash !== 'undefined' ){
			return $cash;
		}
		
		$value = $this->_getAcl()->check($group, $aco );
		$this->_cash = Hash::insert( $this->_cash , $cash_key , $value );
		
		return $value;
	}
	
	public function link( $label , $url , $options ){
		
		$action = Hash::get( $url , 'action' );
		$controller = Hash::get( $url , 'controller' );
		
		if( $this->check( $controller , $action ) ){
			return $this->Html->link( $label , $url , $options );
		}
		return '';
	}

}
