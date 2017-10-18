<?php

foreach ($skills as $skill) {
    echo '<div class="card d-inline-block m-1 "><div class="card-body px-1 py-0">';
    echo $skill->path . ' > ' . $skill->label;
    echo '</div></div>';
}
