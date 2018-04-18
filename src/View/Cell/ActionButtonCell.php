<?php

namespace App\View\Cell;

use Cake\View\Cell;
use App\View\loginUserTrait;
use App\Defines\Defines;
use Cake\Utility\Hash;
use Cake\Controller\ComponentRegistry;
use Acl\Controller\Component\AclComponent;

/**
 * ActionButton cell
 */
class ActionButtonCell extends Cell {

    use loginUserTrait;

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = ['ownerOnly'];
    protected $ownerOnly = false;

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($controller, $action, $target) {

        $this->controller = $controller;
        $this->action = $action;
        $this->target = $target;

        $this->url = $this->_getUrl();

        $label = Hash::get(Defines::ACTION_LABEL, $this->action, $this->action);
        if ($this->action == 'delete') {
            $color = 'danger';
        } else {
            $color = 'primary';
        }

        $this->set([
            'url' => $this->url,
            'action' => $this->action,
            'label' => $label,
            'color' => $color
        ]);
    }

    protected function _getUrl() {

        if (!$this->_isEnable()) {
            return null;
        }

        $url = [
            'controller' => $this->controller,
            'action' => $this->action,
        ];

        if (is_numeric($this->target)) {
            $id = $this->target;
        } elseif (isset($this->target->id)) {
            $id = $this->target->id;
        } else {
            $id = null;
        }

        if ($id) {
            array_push($url, $id);
        }


        return $url;
    }

    protected function _isEnable() {
        $loginUserGroup = $this->getLoginUser('group_id');

        if ($loginUserGroup == Defines::GROUP_ADMIN) {
            return true;
        }

        $loginUserId = $this->getLoginUser('id');
        if ($this->ownerOnly) {
            
        }
    }

    protected function _checkAcl() {
        $Collection = new ComponentRegistry();
        $acl = new AclComponent($Collection);

        $controller = $this->controller;
        $action = $this->action;
        $loginUserId = $this->getLoginUser('id');

        return $acl->check(['Users' => ['id' => $loginUserId]], "$controller/$action");
    }

}
