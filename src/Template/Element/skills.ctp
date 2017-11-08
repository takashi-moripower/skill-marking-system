<?php

if (!isset($cardClass)) {
    $cardClass = null;
}
foreach ($skills as $skill) {
    echo '<div class="card d-inline-block m-1 ' . $cardClass . '"><div class="card-body px-1 py-0">';
    echo $skill->label . '-' . $skill->level;
    echo '</div></div>';
}
