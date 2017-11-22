<?php

isset($user_id);

use Cake\Utility\Hash;
use App\Model\Entity\Skill;

$loginUserId = $this->getLoginUser('id');
$ownerId = isset($user_id) ? $user_id : 0;


if (!is_array($skills)) {
    $skills = $skills->toArray();
}


$skillsByOwner = Hash::filter($skills, function($skill) use($ownerId) {
            $markerId = Skill::getMarkerId($skill);
            return ($markerId == $ownerId);
        });

$skillsByOther = Hash::filter($skills, function($skill) use( $ownerId) {
            $markerId = Skill::getMarkerId($skill);
            return ($markerId != $ownerId);
        });

$skillsByOtherMax = Hash::filter($skillsByOther, function($skill) use($skillsByOther) {

            $levels = [0];
            $levels = array_merge($levels, Hash::extract($skillsByOther, "{n}[id={$skill->id}]._joinData.level"));
            $levels = array_merge($levels, Hash::extract($skillsByOther, "{n}[id={$skill->id}].level"));

            $maxLevel = max($levels);

            return $maxLevel == Hash::get($skill, '_joinData.level', Hash::get($skill, 'level'));
        });

foreach ($skillsByOtherMax as $skill) {

    if (Skill::getMarkerId($skill) == $loginUserId) {
        $class = "bg-skill-loginuser border-dark";
    } else {
        $class = "border-dark";
    }
    echo $this->Element('skills/skill', compact('skill', 'class'));
}

foreach ($skillsByOwner as $skill) {
    $class = "bg-skill-owner border-dark";

    echo $this->Element('skills/skill', compact('skill', 'class'));
}
?>