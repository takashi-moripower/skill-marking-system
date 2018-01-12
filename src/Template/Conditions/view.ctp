<?php

use App\Defines\Defines;
use App\Utility\MyUtil;
use Cake\Routing\Router;

$location_valid = isset($condition->location);
$date_valid = isset($condition->dateStart);

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group_id');

$url = Router::url(null, true);

$Contacts = \Cake\ORM\TableRegistry::get('Contacts');
?>
<div class="card">
    <div class="card-header">
        <div class="h4 m-0">
            人材募集詳細　
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table m-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="w-80 border-top-0"><?= h($condition->title) ?></td>
                </tr>
                <tr>
                    <th>登録者</th>
                    <td><?= h($condition->user->name) ?></td>
                </tr>
                <tr>
                    <th>説明</th>
                    <td><?= MyUtil::strip_tags($condition->note) ?></td>
                </tr>
            </tbody>
            <?php if ($location_valid): ?>
            <tbody class="option" role="location" option_type="1">
                <tr>
                    <th>開催地</th>
                    <td>
                        <?= h($condition->location) ?>
                    </td>
                </tr>
            </tbody>
            <?php endif; ?>
            <?php if ($date_valid): ?>
                <tbody class="option" role="date" option_type="2">
                    <tr>
                        <th>期間</th>
                        <td>
                            <?= MyUtil::dateFormat($condition->date_start) ?> - <?= MyUtil::dateFormat($condition->date_end) ?>
                        </td>
                    </tr>
                </tbody>
            <?php endif; ?>
            <tbody class="skills">
                <tr>
                    <th>スキル条件</th>
                    <td>
                        <?= $this->Element('skills/skills',['skills'=>$condition->skills]);?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="text-right my-2">
    <?php if ($loginUserGroup == Defines::GROUP_ENGINEER): ?>
        <?php if ($Contacts->isExists($condition->id, $loginUserId)): ?>
            <button class="btn btn-sm btn-outline-dark" disabled="disabled" type"button">登録済</button>
        <?php else: ?>
            <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'add']]) ?>
            <?= $this->Form->hidden('user_id', ['value' => $loginUserId]) ?>
            <?= $this->Form->hidden('condition_id', ['value' => $condition->id]) ?>
            <?= $this->Form->hidden('callback_url', ['value' => $url]) ?>
            <?= $this->Form->hidden('flags', ['value' => Defines::CONTACT_FLAG_FROM_ENGINEER]) ?>
            <button class="btn btn-sm btn-outline-primary">コンタクト要求</button>
            <?= $this->Form->end(); ?>
        <?php endif; ?>
    <?php else: ?>
        <?= $this->Html->link('該当ユーザーを検索', ['controller' => 'engineers', 'action' => 'index', 'condition_id' => $condition->id, 'clear' => 1], ['class' => 'btn btn-outline-primary']); ?>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="h4 m-0">
            応募状況　
        </div>
    </div>
    <div class="card-body p-0">
        <?= $this->Element('contacts/list') ?>        
    </div>
</div>
