<?php

use Cake\Utility\Hash;
use App\Model\Entity\Skill;
use App\Defines\Defines;

$loginUserId = $this->getLoginUser('id');
$ownerId = isset($user_id) ? $user_id : 0;

if( !isset( $flags )){
    $flags = 0;
}

if (!is_array($skills)) {
    $skills = $skills->toArray();
}


$skillsByOwner = Skill::findByMarker($skills, $ownerId);
if ($loginUserId != $ownerId) {
    $skillsByLoginUser = Skill::findByMarker($skills, $loginUserId);
}else{
    $skillsByLoginUser = [];
}
$skillsByOther = Skill::findByMarker($skills, [$ownerId, $loginUserId], true);

if (!($flags && Defines::SKILL_DISPLAY_FLAG_DETAILED_OWNER)) {
    $skillsByOwner = Skill::findMaxLevel($skillsByOwner);
}
if (!($flags && Defines::SKILL_DISPLAY_FLAG_DETAILED_VIEWER)) {
    $skillsByLoginUser = Skill::findMaxLevel($skillsByLoginUser);
}
if (!($flags && Defines::SKILL_DISPLAY_FLAG_DETAILED_OTHERS)) {
    $skillsByOther = Skill::findMaxLevel($skillsByOther);
}


foreach ($skillsByOther as $skill) {
    $class = "";
    echo $this->Element('skills/skill', compact('skill', 'class'));
}

foreach ($skillsByLoginUser as $skill) {
    $class = "bg-skill-loginuser ";
    echo $this->Element('skills/skill', compact('skill', 'class'));
}

foreach ($skillsByOwner as $skill) {
    $class = "bg-skill-owner ";
    echo $this->Element('skills/skill', compact('skill', 'class'));
}
?>