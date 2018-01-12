<?php

use App\Defines\Defines;


$isConditionSearch = !empty($this->request->getData('condition_id'));
?>
<?php
if ($isConditionSearch) {
    echo $this->Element('engineers/index_condition');
    $condition_id = $this->request->getData('condition_id');
} else {
    echo $this->Element('engineers/search_form');
}
?>
<?= $this->Element('engineers/index') ?>

<?= $this->Element('paginator'); ?>

<?php $this->append('script') ?>
<script>
    $(function () {
        $('.hint').tooltip();
    });
</script>
<?php $this->end() ?>