<?php

use App\Defines\Defines;

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group_id');
?>
<?php if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_MARKER])): ?>
    <div class="text-right  mb-2">
        <?= $this->Html->link('新規追加', ['action' => 'add'], ['class' => 'btn btn-outline-primary']) ?>
    </div>
<?php endif; ?>
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>名称</th>
            <th>主催</th>
            <th>スキル</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($conditions as $condition): ?>
            <tr>
                <th><?= h($condition->title) ?></th>
                <th><?= h($condition->user->name) ?></th>
                <td>
                    <?php
                    foreach ($condition->skills as $skill) {
                        echo $this->Element('conditions/index_skill', compact('skill'));
                    }
                    ?>
                </td>
                <td class="py-0 align-middle">
                    <?php if (in_array($loginUserGroup, [Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_ENGINEER])): ?>
                        <?= $this->Html->link('詳細', ['controller' => 'conditions', 'action' => 'view', $condition->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                        <?= $this->Html->link('検索', ['controller' => 'engineers', 'action' => 'index', 'condition_id' => $condition->id, 'clear' => 1], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php else: ?>
                        <?= $this->Html->link('検索', ['controller' => 'engineers', 'action' => 'index', 'condition_id' => $condition->id, 'clear' => 1], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                        <?= $this->Html->link('編集', ['controller' => 'conditions', 'action' => 'edit', $condition->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                        <?= $this->Html->link('削除', '', ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                        <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'conditions', 'action' => 'delete', $condition->id], 'object_id' => $condition->id, "role" => "delete"]) ?>
                        <?= $this->Form->end() ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
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