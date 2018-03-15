<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Defines\Defines;

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
class Contact extends Entity {

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

    protected function _getStateEngineer() {
        if ($this->flags & Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER) {
            return Defines::CONTACT_STATE_ALLOW;
        } else if ($this->flags & Defines::CONTACT_FLAG_DENIED_BY_ENGINEER) {
            return Defines::CONTACT_STATE_DENY;
        } else {
            return Defines::CONTACT_STATE_UNDEFINED;
        }
    }
    protected function _getStateTeacher() {
        if ($this->flags & Defines::CONTACT_FLAG_ALLOW_BY_TEACHER) {
            return Defines::CONTACT_STATE_ALLOW;
        } else if ($this->flags & Defines::CONTACT_FLAG_DENIED_BY_TEACHER) {
            return Defines::CONTACT_STATE_DENY;
        } else {
            return Defines::CONTACT_STATE_UNDEFINED;
        }
    }
    protected function _getStateCompany() {
        if ($this->flags & Defines::CONTACT_FLAG_ALLOW_BY_COMPANY) {
            return Defines::CONTACT_STATE_ALLOW;
        } else if ($this->flags & Defines::CONTACT_FLAG_DENIED_BY_COMPANY) {
            return Defines::CONTACT_STATE_DENY;
        } else {
            return Defines::CONTACT_STATE_UNDEFINED;
        }
    }
    
    public function getState($group_id){
        switch( $group_id ){
            case Defines::GROUP_ENGINEER:
                return $this->state_engineer;
                
            case Defines::GROUP_MARKER:
                return $this->state_company;
                
            case Defines::GROUP_ADMIN:
            case Defines::GROUP_ORGANIZATION_ADMIN:
                return $this->state_teacher;
        }
    }
    
    public function clearFlag( $flags ){
        $this->flags &= $this->flags ^ $flags;
    }
    
    public function addFlag( $flags ){
        $this->flags |= $flags;
    }
}
