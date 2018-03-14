<?php
$menu_items = [
    'Groups' => [
        'グループ一覧' => ['action' => 'index'],
        'グループ追加' => ['action' => 'add'],
        'アクション更新' => ['action' => 'update'],
        'アクション初期化 ' => ['action' => 'format'],
        'アクセス権編集' => ['action' => 'permit'],
    ],
    'Aros' => [
        'Aros一覧' => ['action' => 'aros-index'],
        'Aros初期化' => ['action' => 'aros-clear'],
        'グループ情報初期化' => ['action' => 'aros-update-groups'],
        'ユーザー情報初期化' => ['action' => 'aros-update-users'],
    ]
];
?>

<?php foreach ($menu_items as $label => $items): ?>
    <div class="card mb-2">
        <div class="card-header py-1">
            <?= $label ?> Menu
        </div>
        <div class="card-body">
            <ul class="nav flex-column">
                <?php foreach ($items as $label => $url): ?>
                    <li class="nav-item">
                        <a href="<?= $this->Url->build($url) ?>" class="nav-link">
                            <?= $label ?>
                        </a> 
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
<?php endforeach; ?>