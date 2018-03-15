<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$searchFormOpen = $this->request->getData('state_engineer') === '' || $this->request->getData('state_teacher') === '' || $this->request->getData('state_company') === '';

$this->Form->templates(
        Defines::FORM_TEMPLATE_RADIO
);
?>
<div class="card mt-2 border-primary">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'contacts', 'action' => 'index']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row mt-0 mb-1">
                <div class="col-9">
                    <?= $this->Form->text('keyword', ['placeHolder' => 'keyword', 'class' => 'w-100 mt-1']) ?>
                </div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'contacts', 'action' => 'index', 'clear' => 1]) ?>">クリア</a>
                    <button class="btn btn-outline-primary ml-auto" type="button" data-toggle="collapse" data-target="#searchExtends" area-expanded="true" aria-controls="searchExtends"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
            <div id="searchExtends" class="collapse <?= $searchFormOpen ? 'show' : '' ?>" >
                <div class="form-group row my-0">
                    <div class="col-2">
                        学生
                    </div>
                    <div class="col-7">
                        <?= $this->Form->radio('state_engineer', Defines::CONTACT_STATES_ENGINEER, ['empty' => 'すべて', 'default' => '']) ?>
                    </div>
                </div>
                <div class="form-group row my-0">
                    <div class="col-2">
                        教師
                    </div>
                    <div class="col-7">
                        <?= $this->Form->radio('state_teacher', Defines::CONTACT_STATES_TEACHER, ['empty' => 'すべて', 'default' => '']) ?>
                    </div>
                </div>
                <div class="form-group row my-0">
                    <div class="col-2">
                        企業
                    </div>
                    <div class="col-7">
                        <?= $this->Form->radio('state_company', Defines::CONTACT_STATES_COMPANY, ['empty' => 'すべて', 'default' => '']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

