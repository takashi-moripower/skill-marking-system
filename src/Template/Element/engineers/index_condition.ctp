<?php

use Cake\ORM\TableRegistry;

$condition = TableRegistry::get('Conditions')->get($this->request->data['condition_id']);
?>
<div class="card mt-2 border-primary">
    <div class="card-body py-2 px-3">
        「<?= $this->Html->link( $condition->title ,['controller'=>'conditions','action'=>'view',$condition->id] ) ?>  」に適合する学生
        <div class="float-right">
            <a class="btn btn-sm btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'engineers', 'action' => 'index', 'clear' => 1]) ?>">クリア</a>
        </div>
    </div>
</div>
