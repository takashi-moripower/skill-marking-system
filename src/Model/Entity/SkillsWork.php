<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SkillsWork Entity
 *
 * @property int $id
 * @property int $skill_id
 * @property int $work_id
 * @property int $user_id
 * @property int $level
 *
 * @property \App\Model\Entity\Skill $skill
 * @property \App\Model\Entity\Work $work
 * @property \App\Model\Entity\User $user
 */
class SkillsWork extends Entity {

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
