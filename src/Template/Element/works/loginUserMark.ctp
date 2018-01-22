<?php

use App\Defines\Defines;

$levels = \App\Utility\MyUtil::getSkillLevels();


$this->Form->templates(Defines::FORM_TEMPLATE_INLINE_CONTAINER + Defines::FORM_TEMPLATE_RADIO_SKILL_LEVELS);
?>

<div class="card bg-skill-loginuser border-dark mb-1">
    <div class="card-body p-1">
        <?= $this->Form->create(null, ['class' => 'form-inline clearfix form-edit']); ?>
        <?= $this->Form->hidden('user_id', ['value' => $loginUserId]); ?>
        <?= $this->Form->hidden('work_id', ['value' => $work->id]); ?>
        <?= $this->Form->hidden('skill_id', ['value' => $skill->id]); ?>
        <div class="col-6">
            <?= h($skill->label) ?>
            <?php if (isset($skillUpdated) && ( $skillUpdated == $skill->id )): ?>
                <span class="badge badge-danger">updated</span>
            <?php endif; ?>
        </div>
        <div class="col-5 text-right">
            <div class="btn-group">
                <?php
                foreach ($levels as $level) {
                    if ($level == $skill->level) {
                        echo $this->Form->button($level, ['class' => 'btn btn-skill-selector active', 'type' => 'button']);
                    } else {
                        echo $this->Form->button($level, ['class' => 'btn btn-skill-selector', 'type' => 'submit', 'name' => 'level', 'value' => $level]);
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-1 text-right px-0">

            <?php
            if ($skill->level == 0) {
                echo $this->Form->button('削除', ['class' => 'btn btn-outline-danger btn-sm ml-1 bg-weak-white', 'type' => 'button', 'disabled' => 'disabled']);
            } else {
                echo $this->Form->button('削除', ['class' => 'btn btn-outline-danger btn-sm ml-1 bg-weak-white', 'type' => 'submit', 'name' => 'level', 'value' => '0']);
            }
            ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
