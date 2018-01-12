<?php

use App\Defines\Defines;
use Cake\Routing\Router;

$loginUser = $this->request->session()->read('Auth.User');
$url = Router::url(null, true);

switch( $loginUser->group_id ){
    case Defines::GROUP_ADMIN:
    case Defines::GROUP_ORGANIZATION_ADMIN:
        $contact_states = Defines::CONTACT_STATES_TEACHER;
        break;
    case Defines::GROUP_MARKER:
        $contact_states = Defines::CONTACT_STATES_COMPANY;
        break;
    
    case Defines::GROUP_ENGINEER:
        $contact_states = Defines::CONTACT_STATES_ENGINEER;
        break;
}
?>

<table class="table table-bordered table-sm m-0">
    <thead>
        <tr class="">
            <th class="w-15">
                技術者名
            </th>
            <th class="">
                人材募集
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
                <td><?= $this->Html->link( ($contact->condition->published ? '<i class="fa fa-globe"></i> ' : '').h($contact->condition->title), ['controller' => 'conditions', 'action' => 'view', $contact->condition->id],['escape'=>false]) ?></td>
                <?= $this->Element('contacts/td_state',['state'=>$contact->state_engineer,'labels'=>Defines::CONTACT_STATES_ENGINEER]) ?>
                <?= $this->Element('contacts/td_state',['state'=>$contact->state_teacher,'labels'=>Defines::CONTACT_STATES_TEACHER]) ?>
                <?= $this->Element('contacts/td_state',['state'=>$contact->state_company,'labels'=>Defines::CONTACT_STATES_COMPANY]) ?>
                <td class="action text-right">
                    <?php
                    $state = $contact->getState($loginUser['group_id']);
                    ?>

                    <?php if ($state == Defines::CONTACT_STATE_UNDEFINED): ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'allow'], 'class' => 'd-inline-block']) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button($contact_states[Defines::CONTACT_STATE_ALLOW], ['class' => 'btn btn-outline-primary btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'deny'], 'class' => 'd-inline-block']) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button($contact_states[Defines::CONTACT_STATE_DENY], ['class' => 'btn btn-outline-danger btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                    <?php elseif ($state == Defines::CONTACT_STATE_ALLOW): ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'cancel']]) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button($contact_states[Defines::CONTACT_STATE_ALLOW].'を取消', ['class' => 'btn btn-outline-danger btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                    <?php elseif ($state == Defines::CONTACT_STATE_DENY): ?>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'cancel']]) ?>
                        <?= $this->Form->hidden('contact_id', ['value' => $contact->id]); ?>
                        <?= $this->Form->hidden('callback_url', ['value' => $url]); ?>
                        <?= $this->Form->button($contact_states[Defines::CONTACT_STATE_DENY].'を取消', ['class' => 'btn btn-outline-danger btn-sm ']) ?>
                        <?= $this->Form->end() ?>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

