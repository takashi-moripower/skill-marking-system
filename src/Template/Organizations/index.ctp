<div class="text-right mb-2">
    <?= $this->Html->link('新組織作成', ['controller' => 'organizations', 'action' => 'add'], ['class' => 'btn btn-outline-primary']); ?>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($organizations as $org): ?>
            <tr>
                <td class="text-right"><?= $org->id ?></td>
                <td>
                    <?php for ($i = 0; $i < $org->depth; $i++) : ?>
                        <i class="fa fa-fw"></i>
                        <?php if ($i == $org->depth - 1): ?>
                            <i class="fa fa-share fa-flip-vertical fa-fw"></i>
                        <?php endif ?>
                    <?php endfor; ?>
                    <?= h($org->name) ?>
                </td>
                <td>
                    <?= $this->Html->link('編集', ['controller' => 'organizations', 'action' => 'edit', $org->id], ['class' => 'btn btn-sm btn-outline-primary py-0']) ?>
                    <?= $this->Html->link('削除', '' , ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']) ?>
                    <?= $this->Form->create(null, ['method' => 'POST', 'url' => ['controller' => 'organizations', 'action' => 'delete', $org->id], 'object_id' => $org->id, "role" => "delete"]) ?>
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
            return false;
        });
    });
</script>
<?php $this->end(); ?>