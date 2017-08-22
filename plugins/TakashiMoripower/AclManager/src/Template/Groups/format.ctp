<?php
$code = sprintf('%04d', rand(0, 9999));
?>
<div class="card">
    <div class="card-header text-center alert alert-danger text-center">
        警告
    </div>
    <div class="card-body">
        <p>権限テーブルを初期化すると、<br>
            すべてのユーザーがすべてのアクションにアクセスできなくなり<br>
            通常の手法では復元できません</p>
        <p>実行する場合は　初期化コード[<?= $code ?>]を入力してください</p>
    </div>
</div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="card mt-4 col-4">
            <div class="card-body">
                <?= $this->Form->create(NULL) ?>
                <div class="form-group">
                    <?= $this->Form->input('code', ['class' => 'form-control', 'placeHolder' => 'Code', 'label' => false]) ?>
                </div>
                <?= $this->Form->hidden('code2', ['value' => $code,]) ?>
                <div class="text-right">
                    <?= $this->Form->button(__('実行'), ['class' => 'btn btn-danger']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>    
</div>
