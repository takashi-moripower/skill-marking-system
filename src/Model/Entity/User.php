<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * User Entity
 *
 * @property int $id
 * @property int $group_id
 * @property string $name
 * @property string $password
 * @property string $google
 * @property string $facebook
 * @property string $twitter
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\Engineer[] $engineers
 * @property \App\Model\Entity\Marker[] $markers
 */
class User extends Entity {

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

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($password) {
        if ($password == '') {
            return $this->password;
        }
        return (new DefaultPasswordHasher)->hash($password);
    }

    public function parentNode() {
        if (!$this->id) {
            return null;
        }
        if (isset($this->group_id)) {
            $group_id = $this->group_id;
        } else {
            $users_table = TableRegistry::get('Users');
            $user = $users_table->find('all', ['fields' => ['group_id']])->where(['id' => $this->id])->first();
            $group_id = $user->group_id;
        }
        if (!$group_id) {
            return null;
        }

        return ['Groups' => ['id' => $group_id]];
    }

    /*
      protected function _getSkills(){
      $tableW = TableRegistry::get('works');

      $works = $tableW->find()
      ->contain('Skills')
      ->where(['user_id'=>$this->id])
      ->toArray();

      $skills = Hash::extract($works,"{n}.skills.{n}");
      $skills = Hash::sort($skills,'{n}._joinData.level','desc');
      $skills = Hash::sort($skills,'{n}.id','asc');
      return $skills;
      }
     */

    protected function _getSkills() {
        $tableS = TableRegistry::get('skills');

        $skills = $tableS->find('byUser',['user_id' =>$this->id,]);

        return $skills;
    }

    protected function _getMaxSkills() {
        $tableS = TableRegistry::get('skills');
        
        $skills = $tableS->find('MaxLevel',['user_id' => $this->id])
                ->order(['level'=>'DESC','skill_id'=>'ASC'])
;        
        return $skills;
    }

}
