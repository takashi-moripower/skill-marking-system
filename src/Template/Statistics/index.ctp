<?php

use App\Defines\Defines;

$mode = $this->getMode();
?>
<ul class="nav flex-column">
    <li class="nav-item">
        <?= $this->Html->Link('<i class="fa fa-square"></i> 組織別統計', ['action' => 'skills'], ['class' => 'nav-link', 'escape' => false]); ?>
    </li>
    <?php if ($mode == Defines::MODE_MATCHING): ?>
        <li class="nav-item">
            <?= $this->Html->Link('<i class="fa fa-square"></i> 人材募集別統計', ['action' => 'conditions'], ['class' => 'nav-link', 'escape' => false]); ?>
        </li>
    <?php endif ?>
</ul>

