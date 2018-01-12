
<button type="button" class="btn btn-outline-primary hint btn-sm py-0" data-toggle="tooltip" data-html="true" title="<div class='text-left'><?= $message ?></div>">
    <i class="fa fa-question"></i>
</button>

<?php $this->append('script') ?>
<script>
    $(function () {
        $('.hint').tooltip();
    });
</script>
<?php $this->end() ?>