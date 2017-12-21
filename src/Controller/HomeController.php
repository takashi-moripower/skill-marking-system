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
        $user = $this->Auth->user();

        $tableOrgs = TableRegistry::get('Organizations');
        $tableWorks = TableRegistry::get('works');
        $tableOU = TableRegistry::get('organizations_users');

        $orgs = $tableOrgs
                ->find('user', ['user_id' => $user->id, 'relation' => 'children'])
        ;


        $collections = [];
        foreach ($orgs as $org) {
            $collections[$org->id] = [
                'all' => $tableWorks
                        ->find('Organization', ['organization_id' => $org->id])
                        ->count(),
                'marked' => $tableWorks
                        ->find('Organization', ['organization_id' => $org->id])
                        ->find('MarkState', ['mark-state' => Defines::MARK_STATE_MARKED])
                        ->count(),
                'unmarked' => $tableWorks
                        ->find('Organization', ['organization_id' => $org->id])
                        ->find('MarkState', ['mark-state' => Defines::MARK_STATE_UNMARKED])
                        ->count(),
            ];
        }


        $this->set(compact('orgs', 'collections'));

        return $this->render('marker');
    }

    protected function _org_admin() {
        $user = $this->Auth->user();

        $tableOrgs = TableRegistry::get('organizations');
        $tableWorks = TableRegistry::get('works');

        $orgs = $tableOrgs->find('user', ['user_id' => $user->id, 'relation' => 'children']);


        $collections = [];
        foreach ($orgs as $org) {
            $collections[$org->id] = [
                'all' => $tableWorks
                        ->find('Organization', ['organization_id' => $org->id])
                        ->count(),
                'marked' => $tableWorks
                        ->find('Organization', ['organization_id' => $org->id])
                        ->find('Mark', ['mark-state' => Defines::MARK_STATE_MARKED])
                        ->count(),
                'unmarked' => $tableWorks
                        ->find('Organization', ['organization_id' => $org->id])
                        ->find('Mark', ['mark-state' => Defines::MARK_STATE_UNMARKED])
                        ->count(),
            ];
        }


        $this->set(compact('orgs', 'collections'));

        return $this->render('orgAdmin');
    }

    protected function _engineer() {
        $loginUserId = $this->Auth->user('id');

        $contacts = TableRegistry::get('Contacts')
                ->find()
                ->contain(['Users','Conditions'])
                ->where(['Contacts.user_id' => $loginUserId]);

        $this->set(compact('contacts'));
        
        $this->render('engineer');
    }

}
