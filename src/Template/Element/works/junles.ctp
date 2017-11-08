<?php

use Cake\Utility\Hash;
?>
<?php foreach ($work->junles as $junle): ?>
    <div class="card d-inline-block bg-info text-white" junle_id="<?= $junle->id ?>" role="junle-label">
        <div class="card-body p-1">
            <span role="junle-name">
                <?= h($junle->name) ?>
            </span>
            <?= $this->Form->hidden('junles._ids[]', ['value' => $junle->id]) ?>
            <a class="text-white ml-2 cursor-pointer-hover" title="削除" role="remove-junle">
                x
            </a>
        </div>
    </div>
<?php endforeach ?>
<div class="card d-inline-block border-info add-junle">
    <div class="card-body p-0 input-group p-1">
        <?= $this->Form->select('new-junles', $junles) ?>
        <button type="button" class="btn btn-sm btn-info py-1" role="add-junle">追加</button>
    </div>
</div>
<div class="junle-template d-none">
    <div class="card d-inline-block bg-info text-white" junle_id="0" role="junle-label">
        <div class="card-body p-1">
            <span role="junle-name">
            </span>
            <?= $this->Form->hidden('junles._ids[]', ['value' => 0, 'disabled' => 'disabled']) ?>
            <a class="text-white ml-2 cursor-pointer-hover" title="削除" role="remove-junle">
                x
            </a>
        </div>
    </div>
</div>

<?php $this->append('script') ?>
<script>

    var ALL_JUNLES = JSON.parse('<?= json_encode($junles) ?>');

    $(function () {
        checkJunles();

        $(document).on('click', 'button[role="add-junle"]', function (event) {

            junle_id = $('select[name="new-junles"]').val();
            junle_name = ALL_JUNLES[junle_id];

            html = $('.junle-template').html();
            $('.junles .add-junle').before(html);


            newJunle = $('.junles .add-junle').prev('.card[role="junle-label"]');


            newJunle.find('span[role="junle-name"]').text(junle_name);
            newJunle.find('input[type="hidden"]').removeAttr('disabled');
            newJunle.find('input[type="hidden"]').val(junle_id);
            newJunle.attr('junle_id', junle_id);

            checkJunles();
        });

        $(document).on('click', '[role="remove-junle"]', function (event) {
            $(event.target).parents('[role="junle-label"]').remove();

            checkJunles();
        });

        function checkJunles() {

            var junles = [];
            $('.card[junle_id]').each(function (index, obj) {
                junles.push($(obj).attr('junle_id'));
            });

            $('select[name="new-junles"] option').remove();

            $.each(ALL_JUNLES, function (junle_id, name) {

                if ($.inArray(junle_id, junles) == -1) {
                    html = "<option value='" + junle_id + "'>" + name + "</option>";
                    $('select[name="new-junles"]').append(html);
                }
            });
        }
        ;
    });
</script>
<?php
$this->end()?>