<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Defines\Defines;
use App\Utility\MyUtil;

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
        if (isset($value)) {
            return $value;
        }

        $skill = TableRegistry::get('Skills')
                ->find('fieldPath')
                ->where(['Skills.id' => $this->id])
                ->first();

        $path = isset($skill->field_path) ? $skill->field_path : '';

        $this->field_path = $path;
        return $path;
    }

    protected function _getlabel($value) {
        if (isset($value)) {
            return $value;
        }

        $label = $this->field_path . ' > ' . $this->name;
        $this->label = $label;
        return $label;
    }

    protected function _getLevel($value) {
        if (isset($value)) {
            return $value;
        }

        $level = Hash::get($this, '_joinData.level', Hash::get($this, 'SkillsWorks.level'));
        if ($level == null) {
            $level = implode(',', $this->levels);
        }

        $this->level = $level;
        return $level;
    }

    protected function _getLevels($value) {
        if (isset($value)) {
            return $value;
        }

        $levelsFlags = Hash::get($this, '_joinData.levels', 0);
        $levels = MyUtil::flags2Array($levelsFlags);

        return $levels;
    }

    protected function _getMarkerId($value) {
        if (isset($value)) {
            return $value;
        }

        $marker_id = self::getMarkerId($skill);

        $this->marker_id = $marker_id;
        return $marker_id;
    }

    protected function _getFieldOrder($value) {
        if (isset($value)) {
            return $value;
        }

        $field_order = TableRegistry::get('Fields')->get($this->field_id)
                ->lft;
        $this->field_order = $field_order;
        return $field_order;
    }

    static function getMarkerId($skill) {
        if (!is_array($skill)) {
            $skill = $skill->toArray();
        }
        $marker_id = Hash::get($skill, 'marker_id', Hash::get($skill, '_joinData.user_id', Hash::get($skill, 'SkillsWorks.user_id')));
        return $marker_id;
    }

    static function findByMarker($skills, $marker_id, $except = false) {
        if (!is_array($marker_id)) {
            $marker_id = [$marker_id];
        }
        $result = Hash::filter($skills, function($skill) use($marker_id, $except) {
                    $mutch = in_array(self::getMarkerId($skill), $marker_id);
                    return ( $except xor $mutch );
                });

        return $result;
    }

    static function findMaxLevel($skills) {
        if (empty($skills)) {
            return [];
        }

        $result = [];
        foreach ($skills as $skill) {
            if (empty($result[$skill->id]) || $result[$skill->id]->level < $skill->level) {
                $result[$skill->id] = $skill;
            }
        }

        usort($result, function($a, $b) {
            $fl = Hash::get($a, 'field.lft',Hash::get($a,'Fields.lft')) - Hash::get($b, 'field.lft',Hash::get($b,'Fields.lft'));

            if ($fl) {
                return $fl;
            }
            return $b->level - $a->level;
        });

        return $result;
    }

}
