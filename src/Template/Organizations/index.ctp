<?php

use App\Defines\Defines;
?>
<div class="text-right mb-2">
    <?= $this->Html->link('新規追加', ['controller' => 'organizations', 'action' => 'add'], ['class' => 'btn btn-outline-primary']); ?>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>

            <th>名称</th>
            <th class="w-10">組織管理者</th>
            <th class="w-10">採点者</th>
            <th class="w-10">学生</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($organizations as $org): ?>
            <tr>

                <td>
                    <?= h($org->path) ?>
                </td>
                <td class="text-right">
                    <?= $org->count_org_admin ?>
                </td>
                <td class="text-right">
                    <?= $org->count_marker ?>
                </td>
                <td class="text-right">
                    <a href="<?= $this->Url->build(['controller' => 'engineers', 'action' => 'index', 'organization_id' => $org->id, 'clear' => true]) ?>">
                        <?= $org->count_engineer ?>
                    </a>
                </td>
                <td>
                    <?= $this->Html->link('編集', ['controller' => 'organizations', 'action' => 'edit', $org->id], ['class' => 'btn btn-sm btn-outline-primary py-0']) ?>
                    <?= $this->Html->link('登録', ['controller' => 'organizations', 'action' => 'setMembers', $org->id], ['class' => 'btn btn-sm btn-outline-primary py-0']) ?>
                    <?php if( !$org->deletable ):?>
                        <button class="btn btn-sm btn-outline-dark py-0" disabled='disabled' >削除</button>
                    <?php else: ?>
                        <?= $this->Html->link('削除', '', ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                        <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'organizations', 'action' => 'delete', $org->id], 'object_id' => $org->id, "role" => "delete"]) ?>
                        <?= $this->Form->end() ?>
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
        $(document).on('click', 'a.btn[role="delete"]', function (event) {
            if (confirm('realy delete?')) {
                form = $(event.target).siblings('form');
                form.submit();
            }
            return false;
        });
    });
</script>
<?php $this->end(); ?>