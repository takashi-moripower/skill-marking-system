<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use Cake\Utility\Hash;

/**
 * Skill Entity
 *
 * @property int $id
 * @property int $field_id
 * @property string $name
 *
 * @property \App\Model\Entity\Field $field
 * @property \App\Model\Entity\Work[] $works
 */
class Skill extends Entity {

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

    protected function _getFieldPath($value) {
        if( isset( $value )){
            return $value;
        }
        
        $skill = TableRegistry::get('Skills')
                ->find('fieldPath')
                ->where(['Skills.id'=>$this->id])
                ->first();
        
        $this->field_path = $skill->field_path;
        return $skill->field_path;
    }
    
    protected function _getlabel($value){
        if( isset($value)){
            return $value;
        }
        
        $label = $this->field_path . ' > ' . $this->name;
        $this->label = $label;
        return $label;
    }
    
    protected function _getLevel( $value ){
        if( isset($value)){
            return $value;
        }
        
        $level = Hash::get($this,'_joinData.level',Hash::get($this,'SkillsWorks.level'));
        
        $this->level = $level;
        return $level;
    }

}
