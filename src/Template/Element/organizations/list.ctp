<?php foreach ($organizations as $org): ?>
    <?= $org->path_name ?>
    <?= ($org !== end($organizations)) ? ',' : '' ?>
<?php endforeach ?>

