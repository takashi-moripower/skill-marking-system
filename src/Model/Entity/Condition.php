<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

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
class Condition extends Entity
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
}
