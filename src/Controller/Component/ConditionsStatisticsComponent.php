<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

class ConditionsStatisticsComponent extends StatisticsComponent {

    public function conditions() {
        $conditions = TableRegistry::get('Conditions')
                ->find('list');


        $this->users = $this->_getUsers();
        $this->works = $this->_getWorks();
        $skills = $this->_getSkills();

        $controller = $this->getController();

        $controller->set('users', $this->users);
        $controller->set('works', $this->works);
        $controller->set('fields', $this->_getFields());
        $controller->set(compact('skills', 'conditions'));
    }

    protected function _getUsers() {
        $condition_id = $this->request->data('condition_id');
        $users = TableRegistry::get('Users')
                ->find()
                ->select('id');

        if (empty($condition_id)) {
            $users->where(['id' => 0]);
            return $users;
        }

        $uc = TableRegistry::get('Contacts')
                ->find()
                ->where(['condition_id' => $condition_id])
                ->select('user_id');

        $contact_state_student = $this->request->data('contact_state_student');
        if ($contact_state_student != null && $contact_state_student != [0, 1, 2]) {
            $flag_conditions = [];

            if (in_array(Defines::CONTACT_STATE_UNDEFINED, $contact_state_student)) {
                $flag_conditions[] = 0;
            }
            if (in_array(Defines::CONTACT_STATE_ALLOW, $contact_state_student)) {
                $flag_conditions[] = Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER;
            }
            if (in_array(Defines::CONTACT_STATE_DENY, $contact_state_student)) {
                $flag_conditions[] = Defines::CONTACT_FLAG_DENIED_BY_ENGINEER;
            }

            $uc->where(['(Contacts.flags & ' . Defines::CONTACT_FLAG_FILTER_ENGINEER . ') IN (' . implode(',', $flag_conditions) . ')']);
        }

        $users->where(['id IN' => $uc]);

        return $users;
    }

    protected function _getWorks() {
        $users = $this->users->cleanCopy()->select('Users.id');

        $works = TableRegistry::get('Works')
                ->find()
                ->where(['Works.user_id IN' => $users]);

        return $works;
    }

}
