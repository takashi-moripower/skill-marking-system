<?php

use App\Defines\Defines;

$searchFormOpen = ( $this->request->getData('skill.1.id') != 0 || $this->request->getData('skill.2.id') != 0);
?>
<div class="card my-2 border-primary">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'users', 'action' => 'index']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row mt-0 mb-1">
                <div class="col-3">
                    <?= $this->Form->select('group_id', $groups, ['class' => 'form-control', 'empty' => '権限 (選択なし）']) ?>
                </div>
                <div class="col-6">
                    <?= $this->Form->text('name' , ['class' => 'form-control', 'placeHolder'=>'名称']) ?>
                </div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'users', 'action' => 'index', 'clear' => 1]) ?>">クリア</a>
                </div>
            </div>
            <div class="form-group row mt-0 mb-1">
                <div class="col-9">
                    <?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control', 'empty' => '組織 (選択なし）']) ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

