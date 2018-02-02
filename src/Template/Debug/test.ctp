<?= $this->Form->create(null, ['method' => 'post', 'enctype' => 'multipart/form-data']) ?>
<input type="hidden" name="MAX_FILE_SIZE" value="300000000">
<?= $this->Form->file('file'); ?>
<?= $this->Form->submit(); ?>
<?= $this->Form->end() ?>


<pre>
<?php
print_r($data); 
?>
</pre>
