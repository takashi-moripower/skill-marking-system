<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$searchFormOpen = ( $this->request->getData('skill.1.id') != 0 || $this->request->getData('skill.2.id') != 0);
$searchFormOpen |= !empty($this->request->getData('max_age'));
$searchFormOpen |= !empty($this->request->getData('min_age'));
$searchFormOpen |= !empty($this->request->getData('sex'));
?>
<div class="card mt-2 border-primary">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'engineers', 'action' => 'index']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row mt-0 mb-1">
                <div class="col-9">
                    <?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control', 'empty' => true]) ?>
                </div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'engineers', 'action' => 'index', 'clear' => 1]) ?>">クリア</a>
                    <button class="btn btn-outline-primary ml-auto" type="button" data-toggle="collapse" data-target="#searchExtends" area-expanded="true" aria-controls="searchExtends"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
            <div id="searchExtends" class="collapse <?= $searchFormOpen ? 'show' : '' ?>" >
                <div class="form-group row my-0">
                    <div class="col-9">
                        <div class='row mb-1'>
                            <div class="col-2">年齢</div>
                            <div class="col-6 p-0">
                                最低：<?= $this->Form->select('min_age', MyUtil::getAges()); ?>　～
                                最高：<?= $this->Form->select('max_age', MyUtil::getAges()); ?>
                            </div>
                        </div>
                        <div class='row mb-1'>
                            <div class="col-2">性別</div>
                            <div class="col-2 p-0"><?= $this->Form->select('sex', Defines::CONDITION_SEX_OPTIONS) ?></div>
                        </div>
                        <?= $this->Element('engineers/skillform', ['i' => 0]) ?>
                        <?= $this->Element('engineers/skillform', ['i' => 1]) ?>
                        <?= $this->Element('engineers/skillform', ['i' => 2]) ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

