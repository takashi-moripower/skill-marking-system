<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Contact Entity
 *
 * @property int $id
 * @property int $condition_id
 * @property int $user_id
 * @property int $flags
 * @property \Cake\I18n\FrozenTime $engineer_date
 * @property \Cake\I18n\FrozenTime $company_date
 * @property \Cake\I18n\FrozenTime $teacher_date
 *
 * @property \App\Model\Entity\Condition $condition
 * @property \App\Model\Entity\User $user
 */
class Contact extends Entity
{

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
    
    protected function _getState(){
        if( $this->company_date && $this->engineer_date && $this->teacher_date ){
            return '成立';
        }
        
        return '未成立';
    }
}
