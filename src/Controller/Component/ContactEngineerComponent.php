<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use DateTime;

class ContactEngineerComponent extends Component {

    public function onAdd() {
        $tableC = TableRegistry::get('Contacts');
        $contact = $tableC->newEntity($this->request->getData());
        $contact->flags = Defines::CONTACT_FLAG_FROM_ENGINEER | Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER;
        $contact->engineer_date = new DateTime();
        return $contact;
    }


    public function allow($contact) {
        $contact->flags |= Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER;
        $contact->teacher_date = new \DateTime;
        return $contact;
    }

    public function deny($contact) {
        $contact->flags |= Defines::CONTACT_FLAG_DENIED_BY_ENGINEER;
        $contact->teacher_date = null;
        return $contact;
    }

    public function cancel($contact) {
        $contact->clearFlag(Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER | Defines::CONTACT_FLAG_DENIED_BY_ENGINEER);
        $contact->teacher_date = null;
        return $contact;
    }

}
