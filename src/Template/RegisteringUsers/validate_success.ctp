<?php

use App\Defines\Defines;
?>
<div class="card card-default mt-3">
    <div class="card-body">
        <p>
            <?= $user->organization->name ?>に登録申請を送りました
        </p>
        <p class="mb-0">
            <?= $user->organization->name ?>による承認がおりると<br/>
            正式に<?= Defines::TITLES[Defines::MODE_MARKING] ?>を利用できるようになります
        </p>
    </div>
</div>
