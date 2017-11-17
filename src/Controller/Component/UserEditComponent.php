<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use App\Defines\Defines;

class UserEditComponent extends Component {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->Users = TableRegistry::get('Users');
    }

    public function edit($user) {
        $controller = $this->getController();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $controller->Flash->success(__('The user has been saved.'));
            } else {
                $controller->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }

        $loginUser = $controller->Auth->user();

        $organizations = TableRegistry::get('Organizations')
                ->find('pathName')
                ->select('id')
                ->order('Organizations.lft')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

        if ($loginUser->group_id != Defines::GROUP_ADMIN) {
            $organizations
                    ->find('user', ['user_id' => $loginUser->id, 'relation' => 'children']);
        }


        $controller->set(compact('user', 'organizations'));
        $controller->render('edit');
    }

}
