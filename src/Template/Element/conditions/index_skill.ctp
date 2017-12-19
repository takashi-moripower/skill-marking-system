<?php
use App\Model\Table\SkillsTable;
?>

<div class="card d-inline-block m-1 ">
    <div class="card-body px-1 py-0">
        <?= $skill->label . ' (' . implode( ',' , SkillsTable::flags2Array( $skill->_joinData->levels ) ) .')'?>
    </div>
</div>