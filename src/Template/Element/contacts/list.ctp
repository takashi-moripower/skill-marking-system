<?php

use App\Defines\Defines;
use Cake\Routing\Router;

$loginUser = $this->request->session()->read('Auth.User');
$url = Router::url(null, true);
?>

<table class="table table-bordered m-0">
    <thead>
        <tr class="">
            <th class="w-15" >技術者名</th>
            <th class="">
                人材募集条件
            </th>
            <th class="">
                学生
            </th>
            <th class="">
                教師
            </th>
            <th class="">
                企業
            </th>
            <th class="">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?= $this->Html->link(h($contact->user->name), ['controller' => 'engineers', 'action' => 'view', $contact->user->id]) ?></td>
                <td><?= $this->Html->link(h($contact->condition->title), ['controller' => 'conditions', 'action' => 'view', $contact->condition->id]) ?></td>
                <td>
                    <?= Defines::CONTACT_STATES[$contact->state_engineer] ?>
                </td>
                <td>
                    <?= Defines::CONTACT_STATES[$contact->state_teacher] ?>
                </td>
                <td>
                    <?= Defines::CONTACT_STATES[$contact->state_company] ?>
                </td>
                <td class="action text-right">
                    <?php
                    $state = $contact->getState($loginUser['group_id']);
                    ?>

                    <?php if ($state == Defines::CONTACT_STATE_UNDEFINED): ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'allow'], 'class' => 'd-inline-block']) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button('承認', ['class' => 'btn btn-outline-primary btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'deny'], 'class' => 'd-inline-block']) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button('否認', ['class' => 'btn btn-outline-danger btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                    <?php elseif ($state == Defines::CONTACT_STATE_ALLOW): ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'cancel']]) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button('承認を取り消す', ['class' => 'btn btn-outline-danger btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                    <?php elseif ($state == Defines::CONTACT_STATE_DENY): ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'cancel']]) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button('否認を取り消す', ['class' => 'btn btn-outline-danger btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

