<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use DateTime;

class ContactsController extends AppController {

    public function add() {
        $user_id = $this->request->data('user_id');
        $condition_id = $this->request->data('condition_id');
        $callback_url = $this->request->data('callback_url');

        $count = $this->Contacts->find()
                ->where([
                    'user_id' => $user_id,
                    'condition_id' => $condition_id
                ])
                ->count();

        if ($count) {
            $this->Flash->error('already exists');
            $this->redirect($callback_url);
        }
        $contact = $this->Contacts->newEntity($this->request->data);

        if ($contact->flags & Defines::CONTACT_FLAG_FROM_ENGINEER) {
            $contact->engineer_date = new DateTime();
        }


        $this->Contacts->save($contact);

        $this->redirect($callback_url);
    }

}
