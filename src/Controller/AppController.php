<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Controller\ComponentRegistry;
use Acl\Controller\Component\AclComponent;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Acl.Acl');


        $this->loadComponent('Auth', [
            'authorize' => 'Controller',
            // 権限無しページに飛ぶと無限ループになったり、変なURLにリダイレクトされるのを防ぐ
            'unauthorizedRedirect' => ['plugin' => NULL, 'controller' => 'pages', 'action' => 'display', 'deny'],
            'authError' => 'アクセス権限がありません',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email']
                ]
            ]
        ]);

        $this->viewBuilder()->setLayout('bootstrap');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(Event $event) {
        // Note: These defaults are just to get started quickly with development
        // and should not be used in production. You should instead set "_serialize"
        // in each action as required.
        if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    public function beforeFilter(Event $event) {

        //デバッグ時はすべてのアクションにアクセス可能にする
        if (Configure::read('debug') && Configure::read('allow_on_debug', 1)) {
            $this->Auth->allow();
        }
    }

    public function isAuthorized($user) {
        $Collection = new ComponentRegistry();
        $acl = new AclComponent($Collection);
        $controller = $this->request->controller;
        $action = $this->request->action;
        return $acl->check(['Users' => ['id' => $user['id']]], "$controller/$action");
    }

    protected function _getCurrentMode() {
        $session = $this->request->Session();
        return $session->read('App.Mode');
    }

    protected function _setCurrentMode($mode) {
        $session = $this->request->Session();
        $session->write('App.Mode', $mode);
    }

    public function paginate($object = null, array $settings = array()) {
        try {
            return parent::paginate($object, $settings);
        } catch (NotFoundException $e) {
            return $this->redirect(['page'=>1]);
        }
    }

}
