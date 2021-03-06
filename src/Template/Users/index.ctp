<?php

use App\Defines\Defines;

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group_id');
?>
<div class="text-right  mb-2">
    <?= $this->Html->link('新規追加', ['action' => 'add'], ['class' => 'btn btn-outline-primary']) ?>
    <?= $this->Html->link('一括追加', ['action' => 'import'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<?php
echo $this->Element('users/search_form');
?>
<table class="table table-bordered table-sm">
    <thead>
        <tr>

            <th><?= $this->Paginator->sort('name', '名称'); ?></th>
            <th><?= $this->Paginator->sort('group_id', '権限'); ?></th>
            <th>組織</th>
            <th>
                操作
                <?= $this->Element('popup_hint',['message'=>'自分自身のアカウントは削除できません<br/>自分の管轄外の組織に所属しているユーザーは削除できません'])?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>

                <th class="w-15"><?= $user->name ?></th>
                <td class="w-15"><?= $user->group->name ?></td>
                <td>
                    <?php foreach ($user->organizations as $org): ?>
                        <?= $org->path_name ?><br/>
                    <?php endforeach ?>
                </td>
                <td class="py-0 text-right w-10">
                    <?= $this->Html->link('編集', ['controller' => 'users', 'action' => 'edit', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php if (($loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN && $user->deletable ) || $loginUserGroup == Defines::GROUP_ADMIN && $user->id != $loginUserId): ?>
                        <?= $this->Html->link('削除', '', ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                        <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'users', 'action' => 'delete', $user->id], 'object_id' => $user->id, "role" => "delete"]) ?>
                        <?= $this->Form->end() ?>
                    <?php else: ?>
                        <button class="btn btn-sm btn-outline-dark py-0" disabled="disabled">削除</button>
                    <?php endif ?>
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