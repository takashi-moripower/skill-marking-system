<?php

use App\Utility\MyUtil;

$levels = MyUtil::getSkillLevels();
?>
<div class="card bg-skill-loginuser border-dark mb-1">
    <div class="card-body p-1">
        <?= $this->Form->create(null, ['class' => 'form-inline clearfix form-add']); ?>
        <?= $this->Form->hidden('user_id', ['value' => $loginUserId]); ?>
        <?= $this->Form->hidden('work_id', ['value' => $work->id]); ?>

        <div class="col-6">
            <?= $this->Form->select('skill_id', $skillsToSet, ['value' => 0, 'empty' => '追加するスキルを選択してください']) ?>
        </div>
        <div class="col-5 text-right">
            <div class="btn-group">
                <?php
                foreach ($levels as $level) {
                    echo $this->Form->button($level, ['class' => 'btn btn-skill-selector', 'type' => 'submit', 'name' => 'level', 'value' => $level , 'disabled'=>'disabled']);
                }
                ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
