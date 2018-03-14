<h2>管理者Home</h2>

<ul class="nav mt-2">
    <li class="nav-item">
        <?= $this->Html->link('Debug', ['controller' => 'debug'], ['class' => 'nav-link']) ?>
        <?= $this->Html->link('Debug - Login As', ['controller' => 'debug','action'=>'loginAs'], ['class' => 'nav-link']) ?>
    </li>
</ul>

<?= $this->Cell('Admin::registeringUsers'); ?>