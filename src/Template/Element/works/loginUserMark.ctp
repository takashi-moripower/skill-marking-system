<?php

use App\Defines\Defines;

$levels = \App\Utility\MyUtil::getSkillLevels();


$this->Form->templates(Defines::FORM_TEMPLATE_INLINE_CONTAINER + Defines::FORM_TEMPLATE_RADIO_SKILL_LEVELS);
?>

<div class="card bg-skill-loginuser border-dark mb-1">
    <div class="card-body py-1 row">
        <div class="col-6">
            <?= h($skill->label) ?>
        </div>
        <div class="col-5 text-right">
            <div class="btn-group">
                <?php
                foreach ($levels as $level) {
                    if ($level == $skill->level) {
                        echo $this->Form->button($level, ['class' => 'btn btn-skill-selector active', 'type' => 'button']);
                    } else {
                        echo $this->Form->button($level, ['class' => 'btn btn-skill-selector', 'type' => 'button', 'level' => $level , 'skill_id'=>$skill->id]);
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-1 text-right">
            <?php
            if ($skill->level == 0) {
                echo $this->Form->button('削除', ['class' => 'btn btn-outline-danger btn-sm ml-1 bg-weak-white', 'type' => 'button', 'disabled' => 'disabled']);
            } else {
                echo $this->Form->button('削除', ['class' => 'btn btn-outline-danger btn-sm ml-1 bg-weak-white', 'type' => 'button', 'level' => '0' ,'skill_id'=>$skill->id]);
            }
            ?>
        </div>
    </div>
</div>
