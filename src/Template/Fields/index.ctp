<?php
use Cake\Utility\Hash;
?>

<div class="text-right mb-2">
    <?= $this->Html->link('新スキル分野作成', ['controller' => 'fields', 'action' => 'add'], ['class' => 'btn btn-outline-primary']); ?>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>管轄組織</th>
            <th>名称</th>
            <th>スキル</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fields as $field): ?>
            <tr>
                <td class="text-right"><?= $field->id ?></td>
                <td><?= Hash::get($field,'organization.name','共通'); ?></td>
                <td>
                    <?php for ($i = 0; $i < $field->depth; $i++) : ?>
                        <i class="fa fa-fw"></i>
                        <?php if ($i == $field->depth - 1): ?>
                            <i class="fa fa-share fa-flip-vertical fa-fw"></i>
                        <?php endif ?>
                    <?php endfor; ?>
                    <?= h($field->name) ?>
                </td>
                <td class="text-right">
                    <a href="<?= $this->Url->build(['controller'=>'skills','action'=>'index','organization_id'=>$field->organization_id,'field_id'=>$field->id])?>">
                    <?= $field->skill_count?>
                    </a>
                </td>
                <td>
                    <?= $this->Html->link('編集', ['controller' => 'fields', 'action' => 'edit', $field->id], ['class' => 'btn btn-sm btn-outline-primary py-0']) ?>
                    <?= $this->Html->link('削除', ['controller' => 'fields', 'action' => 'delete', $field->id], ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                    <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'fields', 'action' => 'delete', $field->id], 'object_id' => $field->id, "role" => "delete"]) ?>
                    <?= $this->Form->end() ?>
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