<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use App\Model\Table\SkillsTable;

/*
 * 
 * @property \App\Model\Table\ConditionsTable $Conditions
 */

class ConditionsController extends AppController {

    public function index() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $query = $this->Conditions->find()
                ->contain(['Skills' => ['sort' => 'Skills.id'], 'Users', 'Contacts']);

        switch ($loginUserGroup) {
            case Defines::GROUP_MARKER:
                $query->where(['Conditions.user_id' => $loginUserId]);
                break;

            case Defines::GROUP_ENGINEER:
                $query->where(['Conditions.published <> 0'])
                        ->find('user', ['user_id' => $loginUserId]);
                break;
        }


        $conditions = $this->paginate($query);

        $this->set(compact('conditions'));
    }

    public function view($id) {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $contacts = $this->Conditions->Contacts->find('visible', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->where(['condition_id'=>$id])
                ->contain('Users')
                ->contain('Conditions');

        $condition = $this->Conditions->get($id, [
            'contain' => [
                'Skills' => ['sort' => 'skill_id'],
                'ConditionOptions',
            ]
        ]);
        $this->set(compact('condition', 'contacts'));
    }

    public function add() {
        $loginUserId = $this->Auth->user('id');
        $condition = $this->Conditions->newEntity(['user_id' => $loginUserId]);
        return $this->_edit($condition);
    }

    public function edit($id) {
        $condition = $this->Conditions->get($id, ['contain' => ['Skills' => ['sort' => 'skill_id'], 'ConditionOptions']]);
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

        $skills = $this->Conditions->Skills->find();

        $skills = SkillsTable::toPathList($skills);

        $this->set(compact('condition', 'skills'));
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
