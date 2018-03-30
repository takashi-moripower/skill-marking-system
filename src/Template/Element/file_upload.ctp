<?php $this->append('script') ?>

<?= $this->Html->script('jQuery-File-Upload/vendor/jquery.ui.widget') ?>
<?= $this->Html->script('jQuery-File-Upload/jquery.iframe-transport') ?>
<?= $this->Html->script('jQuery-File-Upload/jquery.fileupload') ?>
<script>
    $(function () {
        $('#fileupload').fileupload({
            dataType: 'json',
            done: function (e, data) {
                console.log(data.result.data);
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('.progress .progress-bar').css('width', progress + '%');
                $('.progress .progress-bar').attr('aria-valuenow', progress);
            }
        });
    });
</script>
<?php $this->end() ?>

<div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input id="fileupload" name="files[]" data-url="upload" type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
    <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
</div>

<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
</div>
