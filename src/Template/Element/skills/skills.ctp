<?php
foreach ($skills as $skill) {
    $class = "";
    echo $this->Element('skills/skill', compact('skill', 'class'));
}