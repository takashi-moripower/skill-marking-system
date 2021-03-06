
<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$loginUser = $this->request->session()->read('Auth.User');
?>
<?php if (in_array($loginUser['group'], [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN])): ?>
    <div class="text-right mb-2">
        <a href="<?= $this->Url->build(['controller' => 'skills', 'action' => 'add']) ?>" class="btn btn-outline-primary">新規追加</a>
    </div>
<?php endif ?>
<div class="card mb-2">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'skills', 'action' => 'index']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row my-0">
                <div class="col-5 mb-0">
                    <?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control', 'empty' => '管轄組織（指定なし）']) ?>
                </div>
                <div class="col-5 mb-0">
                    <?= $this->Form->select('field_id', $fields, ['class' => 'form-control', 'empty' => 'スキル分野（指定なし）']) ?>
                </div>
                <div class="col-2 mb-0 px-0 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'skills', 'action' => 'index', 'clear' => 1]) ?>">クリア</a>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>

            <th>管轄組織</th>
            <th>スキル分野</th>
            <th>名称</th>
            <th>説明</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($skills as $skill): ?>
            <tr>

                <td>
                    <?= Hash::get($skill, 'org_name', '共通') ?>
                </td>
                <td>
                    <?= h($skill->field_path) ?>
                </td>
                <td>
                    <?= h($skill->name) ?>
                </td>
                <td>
                    <?= h($skill->note) ?>
                </td>
                <td class="pt-0 pb-0 text-right action">
                    <?php if ($skill->editable): ?>
                        <?= $this->Html->link('編集', ['controller' => 'skills', 'action' => 'edit', $skill->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                        <?= $this->Html->link('削除', ['controller' => 'skills', 'action' => 'delete', $skill->id], ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']); ?>
                    <?php else: ?>
                        <a class="btn btn-sm btn-outline-dark pt-0 pb-0 disabled">編集</a>
                        <a class="btn btn-sm btn-outline-dark pt-0 pb-0 disabled">削除</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= $this->Element('paginator') ?>

<?php $this->append('script'); ?>
<script>
    $(function () {
        $(document).on('click', 'a.btn[role="delete"]', function () {
            return confirm('realy delete?');
        });
    });
</script>
<?php $this->end(); ?>