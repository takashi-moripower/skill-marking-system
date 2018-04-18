<?php

foreach ($skills as $skill) {
    if (!isset($class)) {
        $class = "";
    }
    echo $this->Element('skills/skill', compact('skill', 'class'));
}