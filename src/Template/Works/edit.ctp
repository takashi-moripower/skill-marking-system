<?php
if( $this->request->action == 'add' ){
    $title = "作品　新規作成";
}else{
    $title = "作品　編集";
}
?>

<div class="card">
    <div class="card-header">
        <?= $title ?>
    </div>
    <div class="card-body">
        <?= $this->Form->create($work, ['method' => 'post', 'enctype' => 'multipart/form-data']) ?>
        <?= $this->Form->hidden('id') ?>
        <?= $this->Form->hidden('user_id') ?>
        <div class="form-group">
            <?= $this->Form->input('name', ['class' => 'form-control', 'label' => '題名']) ?>
        </div>
        <div class="form-group">
            <label>ジャンル</label>
            <div class="junles">
                <?= $this->element('works/junles') ?>
            </div>
        </div>
        <div class="form-group">
            <?= $this->Form->input('note', ['class' => 'form-control', 'label' => '解説', 'type' => 'textArea']) ?>
        </div>
        <div class="form-group">
            <label>添付ファイル</label>
            <div class="files">
                <?php foreach ($work->files as $fileNo => $file): ?>
                    <div class="input-group mb-2 file">
                        <div class="form-control">
                            <?= $this->Element('files/thumbnail', ['file' => $file]) ?>
                        </div>
                        <label class="mb-0">
                            <span class="input-group-addon del-file">ファイルを削除</span>
                        </label>
                        <?= $this->Form->hidden("file_to_remove[]", ['value' => $file->id, 'disabled' => 'disabled']) ?>
                    </div>
                <?php endforeach; ?>
                <div class="new-files">

                </div>
                <div>
                    <?= $this->Form->button('新規追加', ['class' => 'btn btn-outline-primary add-file', 'type' => 'button']) ?>
                </div>
            </div>
            <div class="d-none file-template">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" placeholder="未選択">
                    <label class="mb-0">
                        <span class="input-group-addon">ファイルを選択</span>
                        <input type="file" class="d-none" name="files[]"/>
                        <?= $this->Form->hidden('files[]["work_id"]', ['value' => $work->id]) ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="text-right">
                <?= $this->Form->button('保存', ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>

</div>

<?php $this->append('script'); ?>
<script>
    $(function () {
        $('button.add-file').on('click', function () {
            html = $('.file-template').html();

            $('.new-files').append(html);
        });

        $(document).on('change', ':file', function () {
            console.log('file changed');
            var input = $(this);
            var numFiles = input.get(0).files ? input.get(0).files.length : 1;
            var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            console.log('filename', label);
            text = input.parent().prev(':text');
            console.log(text);
            text.val(label);
        });

        $(document).on('click', '.del-file', function () {
            $(this).parent().siblings('input[type="hidden"][name="file_to_remove[]"]').removeAttr("disabled");
            $(this).parents(".input-group.file").hide();
        });

    });

</script>
<?php $this->end() ?>