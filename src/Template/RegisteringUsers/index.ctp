<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$loginUserGroup = $this->getLoginUser('group_id');
?>



<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th><?= $this->Form->checkbox('check_all', ['hidden' => false]) ?></th>
            <th>所属</th>
            <th>卒業年</th>
            <th>名称</th>
            <th>Email</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->Form->checkbox('id', ['value' => $user->id, 'hidden' => false]) ?></td>
                <td><?= h($user->organization->name) ?></td>
                <td><?= h($user->graduation_year) ?></td>
                <td><?= h($user->name) ?></td>
                <td><?= h($user->email) ?></td>
                <td class="action">
                    <?= $this->Html->link('承認', ['action' => 'admit', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']) ?>
                    <?= $this->Html->link('削除', ['action' => 'delete', $user->id], ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                    <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['action' => 'delete', $user->id], 'object_id' => $user->id, "role" => "delete"]) ?>
                    <?= $this->Form->end() ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <td colspan="5">
                <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['action' => 'admit-all']]) ?>
                <?= $this->Form->hidden('ids', ['value' => '']) ?>
                <button class="btn btn-sm btn-outline-primary py-0">チェックしたユーザーを一括承認</button>
                <?= $this->Form->end() ?>
            </td>
        </tr>
    </tfoot>
</table>

<?= $this->Element('paginator') ?>

<script>
    $(function () {
        $('input[name="check_all"]').on('change', onCheckAll);

        function onCheckAll(event) {
            var value = $(event.currentTarget).prop('checked');
            console.log(value);

            $('input[name="id"]').prop('checked', value);
        }

        $(document).on('click', 'a.btn[role="delete"]', function (event) {
            if (confirm('realy delete?')) {
                form = $(event.target).siblings('form');
                form.submit();
            }
            return false;
        });

        $(document).on('change', 'input[name="id"]', setIds);

        function setIds() {
            var ids = [];
            $('input[name="id"]').each(function (id, element) {
                if ($(element).prop('checked')) {
                    ids.push($(element).attr('value'));
                }
            });
            console.log(ids);
            var ids_json = JSON.stringify(ids);

            $('input[name="ids"]').val(ids_json);
        }
        
        setIds();

    });
</script>