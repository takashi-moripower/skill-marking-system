<?php

use App\Defines\Defines;
use App\Utility\MyUtil;
use Cake\Utility\Hash;
?>
<table class="table">
    <tbody>
        <tr>
            <th>名称</th>
            <td><?= h($user->name) ?></td>
        </tr>
        <tr>
            <th>権限</th>
            <td><?= h($user->group->name) ?></td>
        </tr>
        <?php if ($user->group_id != Defines::GROUP_ADMIN): ?>
            <tr>
                <th>組織</th>
                <td><?= h(implode(',', Hash::extract($user, 'organizations.{n}.name'))) ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($user->group_id == Defines::GROUP_ENGINEER): ?>
            <tr>
                <th>スキル</th>
                <td>
                    <?php foreach ($user->maxSkills as $skill): ?>
                        <?= h($skill['name']) ?>:
                        <?= h($skill['_joinData']['level']) ?></br>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <th>ユーザ紹介</th>
            <td>
                <?= MyUtil::strip_tags($user->note) ?>
            </td>
        </tr>
    </tbody>
</table>