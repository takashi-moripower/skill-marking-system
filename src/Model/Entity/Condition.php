<?php

namespace App\Model\Entity;

use App\Defines\Defines;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use DateTime;

/**
 * Condition Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $note
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ConditionOption[] $condition_options
 * @property \App\Model\Entity\Skill[] $skills
 */
class Condition extends Entity {

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

    protected function _getLocation($value) {
        return $this->getOpValue(Defines::CONDITION_OPTION_TYPE_LOCATION, $value);
    }

    protected function _setLocation($value) {
        return $this->setOpValue(Defines::CONDITION_OPTION_TYPE_LOCATION, $value);
    }

    protected function _getDateStart($value) {
        return $this->getOpValue(Defines::CONDITION_OPTION_TYPE_DATE_START, $value);
    }

    protected function _setDateStart($value) {
        if (is_array($value)) {
            $value = $value['year'] . '-' . $value['month'] . '-' . $value['day'];
        }

        return $this->setOpValue(Defines::CONDITION_OPTION_TYPE_DATE_START, $value);
    }

    protected function _getDateEnd($value) {
        return $this->getOpValue(Defines::CONDITION_OPTION_TYPE_DATE_END, $value);
    }

    protected function _setDateEnd($value) {
        if (is_array($value)) {
            $value = $value['year'] . '-' . $value['month'] . '-' . $value['day'];
        }
        return $this->setOpValue(Defines::CONDITION_OPTION_TYPE_DATE_END, $value);
    }

    public function getOp($type) {
        foreach ((array) $this->condition_options as $op) {
            if ($type == $op->type) {
                return $op;
            }
        }
        return null;
    }

    public function getOpValue($type, $value) {
        if (isset($value)) {
            return $value;
        }

        $op = $this->getOp($type);
        return $op ? $op->value : null;
    }

    public function setOpValue($type, $value) {
        if( $this->condition_options == null ){
            $this->condition_options = [];
        }
      
        $op = $this->getOp($type);
        if (!$op) {
            $op = TableRegistry::get('ConditionOptions')->newEntity([
                'condition_id' => $this->id,
                'value' => $value,
                'type' => $type,
            ]);
            $this->condition_options[] = $op;
        } else {
            $op->value = $value;
            $this->dirty('condition_options', true);
        }

        return $value;
    }

}
