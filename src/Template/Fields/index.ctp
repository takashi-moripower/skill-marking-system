<?php

use Cake\Utility\Hash;
?>

<div class="text-right mb-2">
    <?= $this->Html->link('新規追加', ['controller' => 'fields', 'action' => 'add'], ['class' => 'btn btn-outline-primary']); ?>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>

            <th>管轄組織</th>
            <th>名称</th>
            <th>説明</th>
            <th>
                スキル数
                <?= $this->Element('popup_hint',['message'=>'下位分野を含む(含まない)']) ?>
            </th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fields as $field): ?>
            <tr>

                <td><?= h(Hash::get($field, 'organization.name', '共通')) ?></td>
                <td>
                    <?= h($field->path) ?>
                </td>
                <td>
                    <?= h($field->note) ?>
                </td>
                <td class="text-right">
                    <a href="<?= $this->Url->build(['controller' => 'skills', 'action' => 'index', 'organization_id' => $field->organization_id, 'field_id' => $field->id, 'clear' => 1]) ?>">
                        <?= sprintf( '%2d ( %2d )' , $field->skill_count_children , $field->skill_count) ?>
                    </a>
                </td>
                <td>
                    <?php if ($field->editable): ?>
                        <?= $this->Html->link('編集', ['controller' => 'fields', 'action' => 'edit', $field->id], ['class' => 'btn btn-sm btn-outline-primary py-0']) ?>
                        <?= $this->Html->link('削除', ['controller' => 'fields', 'action' => 'delete', $field->id], ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                        <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'fields', 'action' => 'delete', $field->id], 'object_id' => $field->id, "role" => "delete"]) ?>
                        <?= $this->Form->end() ?>
                    <?php else: ?>
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
            ;
            return false;
        });
    });
</script>
<?php $this->end(); ?>