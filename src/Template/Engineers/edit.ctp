
<?= $this->Element('users/edit'); ?>
<div class="text-right mt-1">
    <?php
    echo $this->Html->Link('情報', ['controller' => 'engineers', 'action' => 'view', $user->id], ['class' => 'btn btn-outline-primary ml-1']);
    ?>
</div>

