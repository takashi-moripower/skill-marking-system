<?php

use App\Defines\Defines;
use App\Model\Table\SkillsTable;

$active_level = SkillsTable::flags2Array(  $skill->_joinData->levels );
$this->Form->templates(Defines::FORM_TEMPLATE_INLINE_CHECKBOX);
?>

<tr>
    <th>
        スキル
    </th>
    <td>
        <div class='row'>
            <span class='skill_name col-6'>
                <?= $skill->label ?>
            </span>
            <span class="col-4 skill_levels">
                <?= $this->Form->select("skill_and_levels[{$skill->id}]", SkillsTable::getSkillLevels(), ['multiple' => 'checkbox','value'=>$active_level]) ?>
            </span>
            <div class="col-2 text-right">
                <button type='button' class="btn btn-sm btn-outline-danger" name='delete_skill'>
                    削除
                </button>
            </div>
        </div>
    </td>
</tr>
