<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

/**
 * Organization Entity
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 *
 * @property \App\Model\Entity\Organization $parent_organization
 * @property \App\Model\Entity\Field[] $fields
 * @property \App\Model\Entity\Organization[] $child_organizations
 * @property \App\Model\Entity\User[] $users
 */
class Organization extends Entity {

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
        'id' => false
    ];

    protected function _getCountUsers($val) {
        if (isset($val)) {
            return $val;
        }

        $count = TableRegistry::get('organizations_users')
                ->find()
                ->where(['organization_id' => $this->id])
                ->count();

        $this->count_users = $count;
        return $count;
    }
    
    protected function _getCountEngineers($val) {
        if (isset($val)) {
            return $val;
        }

        $count = TableRegistry::get('organizations_users')
                ->find()
                ->join(['users'=>['table'=>'users','conditions'=>['users.id = organizations_users.user_id']]])
                ->where(['organization_id' => $this->id , 'users.group_id'=>Defines::GROUP_ENGINEER])
                ->count();

        $this->count_engineers = $count;
        return $count;
    }

    protected function _getPathName($val) {
        if (isset($val)) {
            return $val;
        }
        
        $tableO = TableRegistry::get('Organizations');
        
        $org = $tableO->find('pathName')
                ->where([$tableO->aliasField('id')=>$this->id])
                ->first();
          
        $this->path_name = $org->path;
        return $org->path;
    }

}
