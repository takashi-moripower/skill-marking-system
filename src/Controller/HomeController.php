<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class HomeController extends AppController {

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'add']);
    }

    public function index() {
        $user = $this->Auth->user();

        if (empty($user)) {
            return $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $group = Hash::get($user, 'group_id', 0);
        switch ($group) {
            case Defines::GROUP_ADMIN:
                return $this->render('admin');

            case Defines::GROUP_MARKER:
                return $this->_marker();

            case Defines::GROUP_ORGANIZATION_ADMIN:
                return $this->_org_admin();

            case Defines::GROUP_ENGINEER:
                return $this->_engineer();
        }
    }

    protected function _marker() {
        $loginUserId = $this->Auth->user('id');
        $tableOrgs = TableRegistry::get('Organizations');

        $organizations = $tableOrgs
                ->find('home',['user_id' => $loginUserId]);
        
        $this->set(compact('organizations'));
        return $this->render('marker');
    }

    protected function _org_admin() {
        $loginUserId = $this->Auth->user('id');
        $tableOrgs = TableRegistry::get('Organizations');

        $organizations = $tableOrgs
                ->find('home',['user_id' => $loginUserId]);
        
        $this->set(compact('organizations'));
        return $this->render('marker');
    }

    protected function _engineer() {
        $loginUserId = $this->Auth->user('id');

        $contacts = TableRegistry::get('Contacts')
                ->find('visible', ['user_id' => $loginUserId, 'group_id' => Defines::GROUP_ENGINEER])
                ->contain(['Users', 'Conditions']);

        $this->set(compact('contacts'));

        $this->render('engineer');
    }

}
