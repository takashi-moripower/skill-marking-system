<?php
if ($this->request->action == 'add') {
    $title = "作品　新規作成";
} else {
    $title = "作品　編集";
}
?>

<div class="card">
    <div class="card-header">
        <?= $title ?>
    </div>
    <div class="card-body p-0">
        <?= $this->Form->create($work, ['method' => 'post', 'enctype' => 'multipart/form-data']) ?>
        <?= $this->Form->hidden('id') ?>
        <?= $this->Form->hidden('user_id') ?>
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="border-top-0 w-20">題名</th>
                    <td class="border-top-0"><?= $this->Form->input('name', ['class' => 'form-control', 'label' => false]) ?></td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td class="junles"> <?= $this->element('works/junles') ?></td>
                </tr>
                <tr>
                    <th>
                        解説
                        <?= $this->Element('popup_hint',['message'=>'動画や写真は、Youtube,instagramへのリンク表示も可能です']);?>
                    </th>
                    <td><?= $this->Form->input('note', ['class' => 'form-control', 'label' => false, 'type' => 'textArea' , 'id'=>'editor']) ?></td>
                </tr>
                <tr>
                    <th>添付ファイル</th>
                    <td>
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
                                    <input type="file" class="d-none" name="files[]"/>
                                    <?= $this->Form->hidden('files[]["work_id"]', ['value' => $work->id]) ?>
                                    <span class="input-group-addon">ファイルを選択</span>
                                </label>
                            </div>
                        </div>                        
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td class="text-right">
                        <?= $this->Form->button('保存', ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
                    </td>
                </tr>
            </tfoot>
        </table>
        <?= $this->Form->end() ?>
    </div>
</div>
<?php if ($this->request->action != 'add'): ?> 
    <div class="text-right mt-1">
        <?php
        echo $this->Html->Link('一覧', ['controller' => 'works', 'action' => 'index'], ['class' => 'btn btn-outline-primary ml-1']);
        echo $this->Html->Link('採点', ['controller' => 'works', 'action' => 'mark', $work->id], ['class' => 'btn btn-outline-primary ml-1']);
        ?>
    </div>
<?php endif; ?>

<?= $this->Form->end ?>
<script>
    // エディタへの設定を適用する
    CKEDITOR.replace('editor', {
        uiColor: '#EEEEEE',
        height: 400,
    });
</script>

<?php $this->append('script' , $this->Html->script('/js/ckeditor/ckeditor.js')) ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        $('button.add-file').on('click', function () {
            html = $('.file-template').html();
            console.log(html);

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