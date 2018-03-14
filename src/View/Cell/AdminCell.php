<?php

namespace App\View\Cell;

use Cake\View\Cell;
use App\View\loginUserTrait;
use App\Defines\Defines;

/**
 * Admin cell
 */
class AdminCell extends Cell {

    use loginUserTrait;

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display() {
        
    }

    public function marks() {
        $loginUserId = $this->getLoginUser('id');
        $organizations = $this->loadModel('Organizations')
                ->find('home', ['user_id' => $loginUserId])
                ->order('Organizations.lft');

        $this->set(compact('organizations'));
    }

    public function registeringUsers() {
        $loginUser = $this->getLoginUser();

        $orgs = $this->loadModel('Organizations')->find()
                ->select('Organizations.id');

        if ($loginUser['group_id'] == Defines::GROUP_ORGANIZATION_ADMIN) {
            $orgs->find('user', ['user_id' => $loginUser['id']]);
        }

        $users = $this->loadModel('RegisteringUsers')
                ->find()
                ->where(['organization_id IN' => $orgs , 'valid_email'=>1]);
        $count_users = $users->count();

        $this->set('count_users', $count_users);
    }

}
