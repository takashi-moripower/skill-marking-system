<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

$loginUser = $this->request->session()->read('Auth.User');
$isConditionSearch = !empty($this->request->getData('condition_id'));
?>
<table class="table table-bordered table-sm mt-2">
    <thead>
        <tr class="">

            <th class="text-nowrap" >名称</th>
            <th class="" >所属</th>
            <th class="">
                <div class="float-left">
                    スキル評価
                    <?= $this->Element('popup_hint', ['message' => 'スキル単位ごとに、作品評価をまとめて　最大レベルのみを表示しています']) ?>
                </div>
                <div class="float-right">
                    <?= $this->Element('skills/samples'); ?>
                </div>
            </th>
            <th class="">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>

                <td class="text-nowrap"><?= h($user->name) ?></th>
                <td class="text-nowrap">
                    <?php foreach ($user->organizations as $org): ?>
                        <div><?= h($org->path_name) ?></div>
                    <?php endforeach ?>
                </td>
                <td class="p-0 align-middle">
                    <?= $this->Element('skills/colored_skills', ['skills' => (array) $user->skills, 'user_id' => $user->id, 'flags' => Defines::SKILL_DISPLAY_FLAG_FOR_ENGINEERS]); ?>
                </td>
                <td class="py-0 align-middle">
                    <?= $this->Html->link('情報', ['controller' => 'engineers', 'action' => 'view', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php
                    if (in_array($loginUser->group_id, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN])) {
                        echo $this->Html->link('編集', ['controller' => 'engineers', 'action' => 'edit', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']);
                    }

                    if ($isConditionSearch) {
                        echo ' ';
                        echo $this->Element('users/set_contact', compact('condition_id', 'user'));
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

