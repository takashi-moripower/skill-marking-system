<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use App\Utility\MyUtil;

/*
 * 
 * @property \App\Model\Table\ConditionsTable $Conditions
 */

class ConditionsController extends AppController {

    public function index() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $this->paginate = [
            'order' => ['title' => 'ASC']
        ];

        $query = $this->Conditions->find()
                ->find('search', ['search' => $this->request->data])
                ->find('setMatch', ['user_id' => $loginUserId])
                ->contain(['Skills' => ['sort' => 'Skills.id'], 'Users', 'Contacts'])
                ->select($this->Conditions)
                ->select($this->Conditions->Users)
        ;

        switch ($loginUserGroup) {
            case Defines::GROUP_MARKER:
                $query->where(['or' => [
                        'Conditions.published <> 0',
                        'Conditions.user_id' => $loginUserId
            ]]);
                break;

            case Defines::GROUP_ENGINEER:
                $orgs1 = TableRegistry::get('Organizations')->find('user', ['user_id' => $loginUserId, 'relation' => 'parents'])
                        ->select('Organizations.id');
                $orgs2 = TableRegistry::get('Organizations')->find('user', ['user_id' => $loginUserId, 'relation' => 'children'])
                        ->select('Organizations.id');

                $companies = TableRegistry::get('OrganizationsUsers')->find()
                        ->select('user_id')
                        ->where(['or' => [
                        'organization_id in' => $orgs1,
                        'organization_id IN' => $orgs2,
                ]]);

                $query->where(['Conditions.published <> 0'])
                        ->where(['Conditions.user_id IN' => $companies]);
                break;

            case Defines::GROUP_ORGANIZATION_ADMIN:
                $orgs_controll = TableRegistry::get('Organizations')
                        ->find('user', ['user_id' => $loginUserId, 'relation' => 'children'])
                        ->select('Organizations.id');
                $conditions = TableRegistry::get('ConditionsOrganizations')
                        ->find()
                        ->where(['organization_id IN' => $orgs_controll])
                        ->select('condition_id')
                        ->group('condition_id');

                $query->where(['or' => [['Conditions.id IN' => $conditions , 'Conditions.published <> 0'], 'Conditions.user_id' => $loginUserId]]);
                break;
        }

        $conditions = $this->paginate($query);

        //検索用
        $condition_owner = $this->Conditions->find()
                ->where(['published' => 1])
                ->select('user_id');

        $companies = $this->Conditions->Users->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                ->where(['id IN' => $condition_owner]);


        $this->set(compact('conditions', 'companies'));
    }

    public function view($id) {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $contacts = $this->Conditions->Contacts->find('visible', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->where(['condition_id' => $id])
                ->contain('Users')
                ->contain('Conditions');

        $condition = $this->Conditions->get($id, [
            'contain' => [
                'Skills' => ['sort' => 'skill_id'],
                'ConditionOptions',
                'Users',
                'Organizations',
            ]
        ]);
        $this->set(compact('condition', 'contacts'));
    }

    public function add() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $condition = $this->Conditions->newEntity(['user_id' => $loginUserId]);

        $organizations = $this->Conditions
                ->Organizations->find();

        if ($loginUserGroup != Defines::GROUP_ADMIN) {
            $organizations->find('user', ['user_id' => $loginUserId, 'relation' => 'chldren']);
        }

        $condition->organizations = $organizations->toArray();

        return $this->_edit($condition);
    }

    public function edit($id) {
        $condition = $this->Conditions->get($id, ['contain' => ['Skills' => ['sort' => 'skill_id'], 'ConditionOptions', 'Organizations']]);
        return $this->_edit($condition);
    }

    protected function _edit($condition) {

        if ($this->request->is(['post', 'patch', 'put'])) {
            $condition = $this->Conditions->patchEntity($condition, $this->request->getData());

            if (!$this->request->getData('location')) {
                $condition->location = null;
            }

            if (!$this->request->getData('date_start')) {
                $condition->date_start = null;
                $condition->date_end = null;
            }

            if ($this->Conditions->save($condition)) {
                $this->Flash->success(__('The condition has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The condition could not be saved. Please, try again.'));
        }

        $skills = MyUtil::toPathList($this->Conditions->Skills->find('usable', ['user_id' => $condition->user_id]));

        $organizations = TableRegistry::get('Organizations')->getListByUser($condition->user_id);

        $this->set(compact('condition', 'skills', 'organizations'));
        $this->render('edit');
    }

    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Conditions->get($id);
        if ($this->Conditions->delete($user)) {
            $this->Flash->success(__('The condition has been deleted.'));
        } else {
            $this->Flash->error(__('The condition could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
