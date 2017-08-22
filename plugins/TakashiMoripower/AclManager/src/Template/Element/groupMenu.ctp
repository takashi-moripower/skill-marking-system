<?php
$menu_items = [
    'グループ一覧' => ['action' => 'index'],
    'グループ追加' => ['action' => 'add'],
    'アクション更新' => ['action' => 'update'],
    'アクション初期化 ' => ['action' => 'format'],
    'アクセス権編集' => ['action' => 'permit'],
];
?>

<div class="card">
    <div class="card-header">
        Menu
    </div>
    <div class="card-body">
        <ul class="nav flex-column">
            <?php foreach ($menu_items as $label => $url): ?>
                <li class="nav-item">
                    <a href="<?= $this->Url->build($url) ?>" class="nav-link">
                        <?= $label ?>
                    </a> 
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>
