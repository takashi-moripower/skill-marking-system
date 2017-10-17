<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

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

    protected function _getLabel() {
        $result = $this->name;

        if (isset($this->_joinData->level)) {
            $result .= "-" . $this->_joinData->level;
        } elseif (isset($this->level)) {
            $result .= "-" . $this->level;
        }

        return $result;
    }
}