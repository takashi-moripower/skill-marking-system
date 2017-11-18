<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$loginUser = $this->request->session()->read('Auth.User');
?>
<div class="text-right mb-2">
    <a href="<?= $this->Url->build(['controller' => 'junles', 'action' => 'add']) ?>" class="btn btn-outline-primary">新規追加</a>
</div>
<table class="table table-bordered table-sm">
    <thead>
        <tr>

            <th>名称</th>
            <th>説明</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($junles as $junle): ?>
            <tr>

                <td><?= $junle->name ?></td>
                <td><?= $junle->note ?></td>
                <td class="pt-0 pb-0 align-middle">
                    <?= $this->Html->link('編集', ['controller' => 'junles', 'action' => 'edit', $junle->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?= $this->Html->link('削除', ['controller' => 'junles', 'action' => 'delete', $junle->id], ['class' => 'btn btn-sm btn-outline-danger py-0', 'role' => 'delete']); ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= $this->Element('paginator') ?>

<?php $this->append('script'); ?>
<script>
    $(function () {
        $(document).on('click', 'a.btn[role="delete"]', function () {
            return confirm('realy delete?');
        });
    });
</script>
<?php $this->end(); ?>