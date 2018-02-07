<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

class ConditionsStatisticsComponent extends StatisticsComponent {

    public function conditions() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');
        
        $conditions = TableRegistry::get('Conditions')
                ->find('list');
        
        switch( $loginUserGroup ){
            case Defines::GROUP_ORGANIZATION_ADMIN:
                $orgs = TableRegistry::get('Organizations')
                    ->find('User',['user_id'=>$loginUserId,'relation'=>'children'])
                    ->select('id');
                $conds = TableRegistry::get('ConditionsOrganizations')
                        ->find()
                        ->where(['ConditionsOrganizations.organization_id IN' => $orgs])
                        ->select('ConditionsOrganizations.condition_id');
                
                $conditions->where(['Conditions.id IN' => $conds]);
                break;
            
            case Defines::GROUP_MARKER:
                $conditions->where(['user_id'=>$loginUserId]);
                break;
        }


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

        $this->_checkFlags($uc, 'contact_state_student', Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER, Defines::CONTACT_FLAG_DENIED_BY_ENGINEER);
        $this->_checkFlags($uc, 'contact_state_teacher', Defines::CONTACT_FLAG_ALLOW_BY_TEACHER, Defines::CONTACT_FLAG_DENIED_BY_TEACHER);
        $this->_checkFlags($uc, 'contact_state_company', Defines::CONTACT_FLAG_ALLOW_BY_COMPANY, Defines::CONTACT_FLAG_DENIED_BY_COMPANY);

        $users->where(['id IN' => $uc]);

        return $users;
    }

    protected function _checkFlags($uc, $request_key, $flag_allow, $flag_deny) {
        $state_requested = $this->request->data($request_key);
        if (
                $state_requested == null ||
                $state_requested == [
            Defines::CONTACT_STATE_UNDEFINED,
            Defines::CONTACT_STATE_ALLOW,
            Defines::CONTACT_STATE_DENY]
        ) {
            return;
        }

        $flag_conditions = [];

        if (in_array(Defines::CONTACT_STATE_UNDEFINED, $state_requested)) {
            $flag_conditions[] = 0;
        }
        if (in_array(Defines::CONTACT_STATE_ALLOW, $state_requested)) {
            $flag_conditions[] = $flag_allow;
        }
        if (in_array(Defines::CONTACT_STATE_DENY, $state_requested)) {
            $flag_conditions[] = $flag_deny;
        }

        $filter = $flag_allow | $flag_deny;
        $uc->where(["(Contacts.flags & {$filter}) IN (" . implode(',', $flag_conditions) . ")"]);
    }

    protected function _getWorks() {
        $users = $this->users->cleanCopy()->select('Users.id');

        $works = TableRegistry::get('Works')
                ->find()
                ->where(['Works.user_id IN' => $users]);

        return $works;
    }

}
