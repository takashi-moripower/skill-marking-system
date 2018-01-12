<?= $this->Element('engineers/index') ?>
<?php $this->append('script') ?>
<script>
    $(function () {
        $('.hint').tooltip();
    });
</script>
<?php $this->end() ?>