<?php
use App\Defines\Defines;
$loginUserId =$this->getLoginUser('id');
?>
<div class="text-right  mb-2">
    <?= $this->Html->link('新規追加',['action'=>'add'],['class'=>'btn btn-outline-primary']) ?>
</div>
<table class="table table-bordered table-sm">
    <thead>
        <tr>

            <th>名称</th>
            <th>権限</th>
            <th>組織</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>

                <th><?= $user->name ?></th>
                <td><?= $user->group->name ?></td>
                <td>
                    <?php foreach($user->organizations as $org): ?>
                    <?= $org->path_name ?><br/>
                    <?php endforeach ?>
                </td>
                <td class="py-0 align-middle">
                    <?= $this->Html->link('編集', ['controller' => 'users', 'action' => 'edit', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php if( $loginUserId == $user->id ): ?>
                    <button class="btn btn-sm btn-outline-danger py-0" disabled="disabled">削除</button>
                    <?php else:?>
                        <?= $this->Html->link('削除', '', ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                        <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'users', 'action' => 'delete', $user->id], 'object_id' => $user->id, "role" => "delete"]) ?>
                        <?= $this->Form->end() ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

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