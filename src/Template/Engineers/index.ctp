<?php

use App\Defines\Defines;

$loginUser = $this->request->session()->read('Auth.User');
?>
<?php
if ($this->request->getData('condition_id')) {
    echo $this->Element('engineers/index_condition');
} else {
    echo $this->Element('engineers/search_form');
}
?>
<table class="table table-bordered mt-2">
    <thead>
        <tr class="">

            <th class="w-15" >名称</th>
            <th class="">
                スキル評価
                <button type="button" class="btn btn-outline-primary hint btn-sm py-0" data-toggle="tooltip" data-html="true" title="<div class='text-left'>各スキルで最大レベルのみ表示</div>">
                    <i class="fa fa-question"></i>
                </button>

            </th>
            <th class="">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>

                <td><?= h($user->name) ?></th>
                <td class="p-0 align-middle">
                    <?= $this->Element('skills', ['skills' => (array) $user->skills, 'user_id' => $user->id]); ?>
                </td>
                <td class="py-0 align-middle">
                    <?= $this->Html->link('情報', ['controller' => 'engineers', 'action' => 'view', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php if (in_array($loginUser->group_id, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN])): ?>
                        <?= $this->Html->link('編集', ['controller' => 'engineers', 'action' => 'edit', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->Element('paginator'); ?>

<?php $this->append('script') ?>
<script>
    $(function () {
        $('.hint').tooltip();
    });
</script>
<?php $this->end() ?>