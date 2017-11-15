<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Cake\Collection\Collection;

/**
 * Work Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $note
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\File[] $files
 * @property \App\Model\Entity\Junle[] $junles
 * @property \App\Model\Entity\Skill[] $skills
 */
class Work extends Entity {

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


    public function getSkillsBy($userId , $except = false ) {
        $c = new Collection($this->skills);
        return $c->filter(function($value, $key)use($userId,$except) {
                    return ($value->_joinData->user_id == $userId) ^ $except;
                });
    }
}
