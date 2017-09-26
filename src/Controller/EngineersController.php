<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class EngineersController extends AppController {

    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Search.Prg', [
        ]);
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $tableU = TableRegistry::get('users');

        $this->paginate = [
            'order' => ['id' => 'ASC'],
        ];

        $query = $tableU
                ->find()
                ->where(['group_id' => Defines::GROUP_ENGINEER])
                ->find('search', ['search' => $this->request->query]);

        $users = $this->paginate($query);


        //検索フォーム用
        $skills = $tableU->works->skills->find('usable', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->find('list');
        
        $skills = [0 => '-'] + $skills->toArray();

        $levels = array_combine(range(1, Defines::SKILL_LEVEL_MAX), range(1, Defines::SKILL_LEVEL_MAX));


        $this->set(compact('users', 'skills', 'levels'));
        $this->viewBuilder()->layout('bootstrap');
    }
    
    public function view($user_id){
        $tableU = TableRegistry::get('users');
        
        $user = $tableU->find()
                ->where(['id'=>$user_id])
                ->contain(['Works'=>['Skills']])
                ->first();
        
        $this->set(compact('user'));
        $this->viewBuilder()->layout('bootstrap');
    }

}
