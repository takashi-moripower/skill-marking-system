<?php $this->append('script'); ?>
<?= $this->Html->script('plupload/plupload.full.min') ?>
<?php $this->end() ?>

<ul id="filelist"></ul>
<br />

<div id="container">
    <a id="browse" href="javascript:;">[Browse...]</a>
    <a id="start-upload" href="javascript:;">[Start Upload]</a>
</div>
<br />

<button class="btn btn-primary btn-debug">debug</button>
<pre id="console"></pre>




<script type="text/javascript">
    var uploader = new plupload.Uploader({
        browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
        url: '<?= $this->Url->build(['controller' => 'debug', 'action' => 'upload']); ?>'
    });




    uploader.init();

    uploader.bind('FilesAdded', function (up, files) {
        var html = '';
        plupload.each(files, function (file) {
            html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
        });
        document.getElementById('filelist').innerHTML += html;
    });


    uploader.bind('UploadProgress', function (up, file) {
        document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
    });

    uploader.bind('Error', function (up, err) {
        document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
    });

    document.getElementById('start-upload').onclick = function () {
        uploader.start();
    };

    uploader.bind('FileUploaded', function (uploader, file, result) {
        console.log(result);
        data = JSON.parse(result.response);
        console.log(data);
    });
    uploader.bind('UploadComplete', function (uploader, files) {
        console.log('complete');
    });

    $(function () {
        $('.btn-debug').on('click', function () {
            var url = "<?= $this->Url->build(['controller' => 'debug', 'action' => 'upload']) ?>";
            var data = [1, 2, 3];

            $.ajax({
                url: url,
                type: 'post',
                data: data
            }).done(function (result) {
                console.log(result);
            });
        });
    });

</script>