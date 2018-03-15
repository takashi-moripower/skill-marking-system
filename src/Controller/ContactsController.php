<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;

class ContactsController extends AppController {

    const GROUP_TYPE = [
        Defines::GROUP_ADMIN => 'teacher',
        Defines::GROUP_ORGANIZATION_ADMIN => 'teacher',
        Defines::GROUP_MARKER => 'company',
        Defines::GROUP_ENGINEER => 'engineer',
    ];

    public function initialize() {
        parent::initialize();

        $loginUserGroup = $this->Auth->user('group_id');

        $this->loadComponent('Component', ['className' => 'Contacts', 'type' => self::GROUP_TYPE[$loginUserGroup]]);
        $this->loadComponent('SearchSession', ['actions'=>['index']]);
    }

    public function index() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $query = $this->Contacts
                ->find('search', ['search' => $this->request->data])
                ->find('visible', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->contain(['Users', 'Conditions']);
        $contacts = $this->paginate($query);

        $this->set('contacts', $contacts);
    }

    public function add() {
        $callback_url = $this->request->data('callback_url');

        if ($this->Contacts->isExists($this->request->data('condition_id'), $this->request->data('user_id'))) {
            $this->Flash->error('すでに申請されています');
            return $this->redirect($callback_url);
        }

        $contact = $this->Component->onAdd();
        if ($this->Contacts->save($contact)) {
            $this->Flash->success('正常に処理されました');
            $this->redirect($callback_url);
        }
    }

    public function allow() {
        $contact_id = $this->request->data('contact_id');
        $callback_url = $this->request->data('callback_url');

        $contact = $this->Contacts->get($contact_id);
        $contact = $this->Component->allow($contact);

        $this->Contacts->save($contact);
        $this->redirect($callback_url);
    }

    public function deny() {
        $contact_id = $this->request->data('contact_id');
        $callback_url = $this->request->data('callback_url');

        $contact = $this->Contacts->get($contact_id);
        $contact = $this->Component->deny($contact);

        $this->Contacts->save($contact);
        $this->redirect($callback_url);
    }

    public function cancel() {
        $contact_id = $this->request->data('contact_id');
        $callback_url = $this->request->data('callback_url');

        $contact = $this->Contacts->get($contact_id);
        $contact = $this->Component->cancel($contact);

        $this->Contacts->save($contact);
        $this->redirect($callback_url);
    }

}
