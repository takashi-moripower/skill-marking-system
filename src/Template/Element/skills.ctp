<?php

use Cake\Utility\Hash;

if (!isset($cardClass)) {
    $cardClass = null;
}
$loginUserId = $this->getLoginUser('id');



$drawFunc = function($skills, $cardClass = null) use($loginUserId){
    foreach ($skills as $skill) {
        if( $loginUserId == $skill->marker_id ){
            $cardClass = 'border-dark bg-success text-white';
        }
        
        echo '<div class="card d-inline-block m-1 ' . $cardClass . '" ><div class="card-body px-1 py-0">';
        echo $skill->label . '-' . $skill->level;
        echo '</div></div>';
    }
};


if (!isset($user_id)) {
    return $drawFunc($skills, $cardClass);
}


$skillsSelf = Hash::filter($skills, function($skill) use($user_id) {
            $marker_id = Hash::get($skill, '_joinData.user_id', Hash::get($skill, 'marker_id'));
            return ( $marker_id == $user_id );
        });
$skillsOther = Hash::filter($skills, function($skill) use($user_id) {
            $marker_id = Hash::get($skill, '_joinData.user_id', Hash::get($skill, 'marker_id'));
            return ( $marker_id != $user_id );
        });
$skillsOtherMax = Hash::filter($skillsOther, function($skill) use($skillsOther) {
            $levels = [0];
            $levels = array_merge($levels, Hash::extract($skillsOther, "{n}[id={$skill->id}]._joinData.level"));
            $levels = array_merge($levels, Hash::extract($skillsOther, "{n}[id={$skill->id}].level"));

            $maxLevel = max($levels);

            return $maxLevel == Hash::get($skill, '_joinData.level', Hash::get($skill, 'level'));
        });
        


$drawFunc($skillsOtherMax);
$drawFunc($skillsSelf, 'border-dark bg-warning');
