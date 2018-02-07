<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\View;

use Cake\View\View;
use Cake\Utility\Inflector;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();
        $this->loadHelper('TimeStamp');
    }

    public function bodyClass() {
        $action = Inflector::dasherize($this->request->action);
        $controller = Inflector::dasherize($this->name);

        return "controller-{$controller} action-{$action}";
    }

    public function getLoginUser($key = null, $default = null) {
        if (isset($key)) {
            $key = 'Auth.User.' . $key;
        } else {
            $key = 'Auth.User';
        }

        $result = $this->request->session()->read($key);

        if ($result === null) {
            return $default;
        }

        return $result;
    }
    
    public function getMode(){
        return $this->request->session()->read('App.Mode');
    }

}
