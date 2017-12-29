<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use DateTime;

class ContactCompanyComponent extends Component {

    public function onAdd() {
        $tableC = TableRegistry::get('Contacts');
        $contact = $tableC->newEntity($this->request->getData());
        $contact->flags = Defines::CONTACT_FLAG_FROM_COMPANY | Defines::CONTACT_FLAG_ALLOW_BY_COMPANY;
        $contact->company_date = new DateTime();
        return $contact;
    }

    public function allow($contact) {
        $contact->flags |= Defines::CONTACT_FLAG_ALLOW_BY_TEACHER;
        $contact->teacher_date = new \DateTime;
        return $contact;
    }

    public function deny($contact) {
        $contact->flags |= Defines::CONTACT_FLAG_DENIED_BY_TEACHER;
        $contact->teacher_date = null;
        return $contact;
    }

    public function cancel($contact) {
        $contact->clearFlag(Defines::CONTACT_FLAG_ALLOW_BY_TEACHER | Defines::CONTACT_FLAG_DENIED_BY_TEACHER);
        $contact->teacher_date = null;
        return $contact;
    }

}
