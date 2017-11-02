<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Field Entity
 *
 * @property int $id
 * @property string $name
 * @property int $organization_id
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 *
 * @property \App\Model\Entity\Organization $organization
 * @property \App\Model\Entity\Field $parent_field
 * @property \App\Model\Entity\Field[] $child_fields
 * @property \App\Model\Entity\Skill[] $skills
 */
class Field extends Entity {

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

    protected function _getPath($value) {

        if ($value) {
            return $value;
        }
        
        $tableF = TableRegistry::get('fields');


        $roots = $tableF->find()
                ->leftJoin(['branch' => 'fields'], ['branch.id' => $this->id])
                ->where([$tableF->aliasField('lft') . ' <= branch.lft', $tableF->aliasField('rght') . ' >= branch.rght'])
                ->order([$tableF->aliasField('lft') => 'ASC']);
        $path = '';

        foreach ($roots as $root) {
            $path .= $root->name;

            if ($root !== end($roots)) {
                $path .= " > ";
            }
        }

        $pathes = Hash::extract($roots->toArray(), '{n}.name');

        $path = implode(' > ', $pathes);

        return $path;
    }

}
