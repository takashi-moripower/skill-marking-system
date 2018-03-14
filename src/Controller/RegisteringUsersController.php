<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use App\Utility\MyUtil;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\Utility\Hash;

/*
 * 
 * @property \App\Model\Table\ConditionsTable $Conditions
 */

class RegisteringUsersController extends AppController {

    public function add() {
        $tableRU = TableRegistry::get('RegisteringUsers');


        $tableO = TableRegistry::get('Organizations');

        $organizations = $tableO->find('root')
                ->find('list')
                ->order('Organizations.lft');

        $user = $tableRU->newEntity();

        do {
            $token = MyUtil::makeRandStr(16);
        } while ($this->RegisteringUsers->find()->where(['token' => $token])->count() != 0);

        if ($this->request->is('post')) {
            $tableRU->patchEntity($user, $this->request->data);

            if ($tableRU->save($user)) {
                $this->set('email', $user->email);
                $this->_sendValidationEmail($user);
                return $this->render('add_success');
            } else {
                $this->Flash->error('登録できませんでした');
            }
        }

        $this->set(compact('user', 'organizations', 'token'));
    }

    protected function _sendValidationEmail($user) {
        $email = new Email;

        $admin_email = Configure::read('Admin.email', ['dummy@dummy.com']);
        $title = Defines::TITLES[Defines::MODE_MARKING];

        $email
                ->from($admin_email, $title)
                ->addTo($user->email, $user->name)
                ->setSubject(Defines::USER_REGISTRY_MAIL_TITLE)
                ->setViewVars(compact('user'))
                ->settemplate('/Email/validation')
                ->send();
    }

    public function validate($token) {
        $user = $this->RegisteringUsers->find()
                ->where(['token' => $token])
                ->contain('Organizations')
                ->first();

        if (!$user) {
            return $this->render('validate_failed');
        }

        $user->valid_email = true;
        $this->RegisteringUsers->save($user);

        $this->set(compact('user'));
        return $this->render('validate_success');
    }

    public function index() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $query = $this->RegisteringUsers->find()
                ->where(['valid_email' => 1])
                ->contain(['Organizations'])
                ->group(['RegisteringUsers.name', 'RegisteringUsers.email', 'RegisteringUsers.organization_id', 'RegisteringUsers.graduation_year', 'RegisteringUsers.valid_email']);
        if ($loginUserGroup != Defines::GROUP_ADMIN) {
            $organizations = $this->RegisteringUsers->Organizations->find('user', ['user_id' => $loginUserId])
                    ->select('id');
            $query->where(['organization_id IN' => $organizations]);
        }

        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);

        $user = $this->RegisteringUsers->get($id);


        if ($this->RegisteringUsers->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
            $this->_sendRefuseEmail($user);
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function admit($id) {
        $this->_admit($id);
        return $this->redirect(['action' => 'index']);
    }

    public function admitAll() {
        $this->request->allowMethod('POST');
        $ids = json_decode($this->request->data('ids'));

        foreach ($ids as $id) {
            $this->_admit($id);
        }

        return $this->redirect(['action' => 'index']);
    }

    protected function _admit($id) {
        $registeringUser = $this->RegisteringUsers->get($id);

        $newUser = $this->RegisteringUsers->admit($registeringUser);

        if ($newUser) {
            $this->Flash->success(Hash::get($newUser, 'name') . '登録しました');
            $this->_sendNoticeEmail($newUser);
        }
    }

    protected function _sendNoticeEmail($user) {
        $email = new Email;

        $admin_email = Configure::read('Admin.email', ['dummy@dummy.com']);
        $title = Defines::TITLES[Defines::MODE_MARKING];

        $email
                ->from($admin_email, $title)
                ->addTo(Hash::get($user, 'email'), Hash::get($user, 'name'))
                ->setSubject(Defines::USER_REGISTRY_MAIL_TITLE)
                ->setViewVars(compact('user'))
                ->settemplate('/Email/registerNotice')
                ->send();
    }
    
    protected function _sendRefuseEmail($user){
        $email = new Email;

        $admin_email = Configure::read('Admin.email', ['dummy@dummy.com']);
        $title = Defines::TITLES[Defines::MODE_MARKING];

        $email
                ->from($admin_email, $title)
                ->addTo(Hash::get($user, 'email'), Hash::get($user, 'name'))
                ->setSubject(Defines::USER_REGISTRY_MAIL_TITLE)
                ->setViewVars(compact('user'))
                ->settemplate('/Email/registerRefuse')
                ->send();
    }

}
