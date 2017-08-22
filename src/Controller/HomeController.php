<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
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
        $this->viewBuilder()->layout('bootstrap');
    }
    
    public function index(){
        $user =         $this->Auth->user();
        $this->set('data',$user);
//        $this->render('/Common/debug');
    }

}
