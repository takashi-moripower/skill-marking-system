<?php

namespace TakashiMoripower\AclManager\Model\Entity;

use Cake\ORM\Entity;
use Acl\Controller\Component\AclComponent;
use Cake\Controller\ComponentRegistry;
use App\Model\Entity\Group as BaseEntity;

/**
 * Group Entity.
 *
 * @property int $id
 * @property string $name
 * @property \App\Model\Entity\User[] $users
 */
class Group extends BaseEntity {

    static $acl = NULL;

    static function getAcl() {
        if (!self::$acl) {
            $collection = new ComponentRegistry();
            self::$acl = new AclComponent($collection);
        }
        return self::$acl;
    }

    public function parentNode() {
        return null;
    }

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    public function check($controller, $action) {

        $aco = 'App/' . $controller . '/' . $action;
        return self::getAcl()->check($this, $aco);
    }

}
