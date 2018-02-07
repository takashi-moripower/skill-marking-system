<?php

use App\Defines\Defines;

?>
<div class="card my-2 border-primary">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'conditions', 'action' => 'index']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row mt-0 mb-1">
                <div class="col-6">
                    <?= $this->Form->select('user_id', $companies, ['class' => 'form-control', 'empty' => '主催者 (選択なし）']) ?>
                </div>
                <div class="col-3">
                    <?= $this->Form->select('match', [null=>'すべての募集を表示',1=>'条件適合のみ'], ['class' => 'form-control']) ?>
                </div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'conditions', 'action' => 'index', 'clear' => 1]) ?>">クリア</a>
                </div>
            </div>
            <div class="form-group row mt-0 mb-1">
                <div class="col-9">
                    <?= $this->Form->text('title' , ['class' => 'form-control', 'placeHolder'=>'名称']) ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

