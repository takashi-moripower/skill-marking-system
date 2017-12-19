<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

class SearchSessionComponent extends Component {

    const SESSION_KEY_PREFIX = 'Search';

    protected $_defaultConfig = [
        'actions' => ['index', 'lookup'],
    ];

    public function startup() {
        if (!in_array($this->request->action, $this->getConfig('actions'))) {
            return false;
        }

        if (Hash::get($this->request->data, 'clear') || Hash::get($this->request->query, 'clear')) {
            $this->_clearSession();
        }
        
        $session = $this->_readSession();
        
        $this->request->data = $this->request->data + $this->request->query + $session;
        
        unset($this->request->data['clear']);
        
        $this->_writeSession( $this->request->data );
        
        return true;
    }

    protected function _getClearUrl() {
        $controller = $this->_registry->getController();

        $name = $controller->name;
        $action = $this->request->action;

        return [
            'controller' => $name,
            'action' => $action,
        ];
    }

    protected function _getSessionKey() {
        $controller = $this->_registry->getController();

        $name = $controller->name;
        $action = $this->request->action;

        $key = self::SESSION_KEY_PREFIX . "." . Inflector::camelize($name) . "." . Inflector::camelize($action);

        return $key;
    }


    protected function _clearSession() {
        $this->request->session()->delete($this->_getSessionKey());
    }

    protected function _readSession() {
        return (array) $this->request->session()->read($this->_getSessionKey());
    }

    protected function _writeSession($value) {
        $this->request->session()->write($this->_getSessionKey(), $value);
    }

}
