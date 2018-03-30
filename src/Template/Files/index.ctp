
<table class="table table-sm table-bordered">
    <?php foreach ($files as $file): ?>
        <tr>
            <td><?= $this->Html->link( $file->name )?></td>
            <td class="text-right"><?= $file->size ?>byte</td>
        </tr>
    <?php endforeach ?>
</table>