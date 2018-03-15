<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use DateTime;
use Cake\Controller\ComponentRegistry;
use Cake\Utility\Hash;

class ContactsComponent extends Component {

    protected $_type;

    const CONFIGURE = [
        'engineer' => [
            'allowFlag' => Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER,
            'denyFlag' => Defines::CONTACT_FLAG_DENIED_BY_ENGINEER,
            'flomFlag' => Defines::CONTACT_FLAG_FROM_ENGINEER,
        ],
        'teacher' => [
            'allowFlag' => Defines::CONTACT_FLAG_ALLOW_BY_TEACHER,
            'denyFlag' => Defines::CONTACT_FLAG_DENIED_BY_TEACHER,
            'flomFlag' => Defines::CONTACT_FLAG_FROM_TEACHER,
        ],
        'company' => [
            'allowFlag' => Defines::CONTACT_FLAG_ALLOW_BY_COMPANY,
            'denyFlag' => Defines::CONTACT_FLAG_DENIED_BY_COMPANY,
            'flomFlag' => Defines::CONTACT_FLAG_FROM_COMPANY,
        ],
    ];

    public function __construct(ComponentRegistry $registry, array $config = array()) {
        parent::__construct($registry, $config);
        $this->_type = Hash::get($config, 'type', 'engineer');
    }

    public function onAdd() {
        $tableC = TableRegistry::get('Contacts');
        $contact = $tableC->newEntity($this->request->getData());
        $contact->flags = $this->_getAllowFlag() | $this->_getFromFlag();

        $date_label = $this->_type . '_date';
        $contact->{$date_label} = new DateTime;
        return $contact;
    }

    public function allow($contact) {
        $contact->clearFlag($this->_getDenyFlag());
        $contact->addFlag($this->_getAllowFlag());

        $date_label = $this->_type . '_date';
        $contact->{$date_label} = new DateTime;

        return $contact;
    }

    public function deny($contact) {
        $contact->clearFlag($this->_getAllowFlag());
        $contact->addFlag($this->_getDenyFlag());

        $date_label = $this->_type . '_date';
        $contact->{$date_label} = null;

        return $contact;
    }

    public function cancel($contact) {
        $contact->clearFlag($this->_getAllowFlag() | $this->_getDenyFlag());

        $date_label = $this->_type . '_date';
        $contact->{$date_label} = null;
        return $contact;
    }

    protected function _getAllowFlag() {
        return Hash::get(self::CONFIGURE, $this->_type . '.allowFlag');
    }

    protected function _getDenyFlag() {
        return Hash::get(self::CONFIGURE, $this->_type . '.denyFlag');
    }

    protected function _getFromFlag() {
        return Hash::get(self::CONFIGURE, $this->_type . '.fromFlag');
    }

}
