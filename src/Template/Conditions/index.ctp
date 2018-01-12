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
<?= $this->Element('conditions/search_form'); ?>
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('published', '公開'); ?></th>
            <th class="w-30"><?= $this->Paginator->sort('title', '名称'); ?></th>
            <th><?= $this->Paginator->sort('user_id', '主催'); ?></th>
            <th class="text-nowrap">
                適合
                <?= $this->Element('popup_hint',['message'=>'あなたのスキルが募集条件に適合している場合　マークが表示されます'])?>
            </th>
            <th class="w-40">スキル</th>
            <?php if ($loginUserGroup != Defines::GROUP_ENGINEER): ?>
                <th>操作</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($conditions as $condition): ?>
            <tr>
                <td class="text-center w-5">
                    <?= $condition->published ? '<i class="fa fa-globe"></i>' : '' ?>
                </td>
                <th><?= $this->Html->link(h($condition->title), ['controller' => 'conditions', 'action' => 'view', $condition->id]) ?></th>
                <th class="text-nowrap"><?= $this->Html->link(h($condition->user->name), ['controller' => 'users', 'action' => 'view', $condition->user_id]) ?></th>
                <td class="text-center w-5">
                    <?= $condition->match ? '<i class="fa fa-hand-peace-o"></i>' : '' ?>
                </td>
                <td>
                    <?php echo $this->element('skills/skills', ['skills' => $condition->skills]) ?>
                </td>
                <?php if ($loginUserGroup != Defines::GROUP_ENGINEER): ?>
                    <td class="py-0 align-middle">
                        <?php if ($loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN): ?>
                            <?= $this->Html->link('検索', ['controller' => 'engineers', 'action' => 'index', 'condition_id' => $condition->id, 'clear' => 1], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                        <?php else: ?>
                            <?= $this->Html->link('検索', ['controller' => 'engineers', 'action' => 'index', 'condition_id' => $condition->id, 'clear' => 1], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                            <?= $this->Html->link('編集', ['controller' => 'conditions', 'action' => 'edit', $condition->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                            <?= $this->Html->link('削除', '', ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                            <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'conditions', 'action' => 'delete', $condition->id], 'object_id' => $condition->id, "role" => "delete"]) ?>
                            <?= $this->Form->end() ?>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
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




