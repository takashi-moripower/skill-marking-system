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

    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    public function index() {
        $query = $this->Conditions->find();

        $conditions = $this->paginate($query);

        $this->set(compact('conditions'));
    }

    public function add() {
        $condition = $this->Conditions->newEntity();
        return $this->_edit($condition);
    }

    public function edit($id) {
        $condition = $this->Conditions->get($id, ['contain' => ['Skills']]);
        return $this->_edit($condition);
    }

    protected function _edit($condition) {
        if ($this->request->is(['post','patch','put'])) {
            $condition = $this->Conditions->patchEntity($condition, $this->request->getData());
            if ($this->Conditions->save($condition)) {
                $this->Flash->success(__('The condition has been saved.'));

                $this->set('data',$this->request->getData());
                return $this->render('/Common/debug');
//                return $this->redirect(['action' => 'index']);
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
