<?php ?>
<?= $this->Form->create($organization) ?>

<div class="card">
    <div class="card-header">
        <h2><?= $organization->name ?>　メンバー設定</h2>
    </div>
    <div class="card-body">

        <?= $this->Form->hidden('id'); ?>

        <?= $this->Form->select('users._ids', $users, ['multiple' => 'checkbox']); ?>
        <div  class="text-right" >
            <?= $this->Form->button('保存', ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
        </div>
    </div>

</div>
<?= $this->Form->end() ?>
