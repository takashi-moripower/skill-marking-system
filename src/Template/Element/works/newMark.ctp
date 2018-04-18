<?php

use App\Utility\MyUtil;

$levels = MyUtil::getSkillLevels();
?>
<div class="card bg-skill-loginuser border-dark mb-1 new-skill">
    <div class="card-body py-1 row">
        <div class="col-6">
            <?= $this->Form->select('new_skill_id', $skillsUnused, ['value' => 0, 'empty' => '追加するスキルを選択してください']) ?>
        </div>
        <div class="col-5 text-right">
            <div class="btn-group">
                <?php
                foreach ($levels as $level) {
                    echo $this->Form->button($level, ['class' => 'btn btn-skill-selector', 'type' => 'button', 'level' => $level, 'disabled' => 'disabled']);
                }
                ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
