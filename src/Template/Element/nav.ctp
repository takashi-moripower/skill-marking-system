<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$group_id = $this->getLoginUser('group_id');
$mode = $this->request->session()->read('App.Mode');

$navs = Hash::get(Defines::NAV_GROUP_TEMPLATES, "{$mode}.{$group_id}", []);

if (empty($navs)) {
    return;
}
?>
<div class="bg-light mb-3">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light  justify-content-between">
            <ul class="nav">
                <?php foreach ($navs as $nav_id): ?>
                    <?php
                    $label = Hash::get(Defines::NAV_TEMPLATES, "{$nav_id}.label");
                    $url = Hash::get(Defines::NAV_TEMPLATES, "{$nav_id}.url");
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
            <div>
                <?php
                if ($mode == Defines::MODE_MARKING) {
                    $next_mode = Defines::MODE_MATCHING;
                    $next_label = "<i class='fa fa-handshake-o'></i>";
                    $next_title = Defines::TITLES[$next_mode];
                } else {
                    $next_mode = Defines::MODE_MARKING;
                    $next_label = "<i class='fa fa-tags'></i>";
                    $next_title = Defines::TITLES[$next_mode];
                }
                echo $this->Html->link($next_label, ['controller' => 'Users', 'action' => 'setMode', $next_mode], ['class' => 'd-inline-block my-2 btn-sm btn btn-mode-' . $next_mode, 'escape' => false, 'title' => $next_title]);
                ?>
            </div>
        </nav>
    </div>
</div>
