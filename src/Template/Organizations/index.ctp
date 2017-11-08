<?php
use App\Defines\Defines;
?>
<div class="text-right mb-2">
    <?= $this->Html->link('新組織作成', ['controller' => 'organizations', 'action' => 'add'], ['class' => 'btn btn-outline-primary']); ?>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>所属人数</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($organizations as $org): ?>
            <tr>
                <td class="text-right"><?= $org->id ?></td>
                <td>
                    <?= h($org->path) ?>
                </td>
                <td>
                    <?= $org->count_org_admin ?>/
                    <?= $org->count_marker ?>/
                    <a href="<?= $this->Url->build(['controller'=>'engineers','action'=>'index','organization_id'=>$org->id,'clear'=>true]) ?>">
                    <?= $org->count_engineer ?>
                    </a>
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