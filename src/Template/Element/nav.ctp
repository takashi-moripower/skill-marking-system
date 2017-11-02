<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$group_id = $this->getLoginUser('group_id');

$navs = Hash::get(Defines::NAV_GROUP_TEMPLATES, $group_id, []);

if( empty($navs)){
    return;
}
?>
<div class="bg-light mb-3">
    <div class="container">
        <div class="row">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <ul class="nav">
                    <?php foreach ($navs as $nav_id): ?>
                        <?php
                        $label = Hash::get(Defines::NAV_TEMPLATES,"{$nav_id}.label");
                        $url = Hash::get(Defines::NAV_TEMPLATES,"{$nav_id}.url");
                        if (Hash::get($url, 'controller') == $this->name) {
                            $class = "nav-link active";
                        } else {
                            $class = "nav-link";
                        }
                        ?>
                        <li class="nav-item">
                            <?= $this->Html->link($label, $url, ['class' => $class]) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
