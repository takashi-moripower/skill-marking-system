<?php

use App\Defines\Defines;
use Cake\Routing\Router;

$Contacts = \Cake\ORM\TableRegistry::get('Contacts');
$loginUserGroup = $this->getLoginUser('group_id');
$url = Router::url(null, true);

switch ($loginUserGroup) {
    case Defines::GROUP_ADMIN:
    case Defines::GROUP_ORGANIZATION_ADMIN:
        $label = '承認';
        break;

    case Defines::GROUP_ENGINEER:
        $label = '申込';
        break;

    case Defines::GROUP_MARKER:
        $label = '勧誘';
        break;
}

if (!$Contacts->isExists($condition_id, $user->id)) {
    echo $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'add'], 'class' => 'd-inline-block']);
    echo $this->Form->hidden('condition_id', ['value' => $condition_id]);
    echo $this->Form->hidden('user_id', ['value' => $user->id]);
    echo $this->Form->hidden('callback_url', ['value' => $url]);
    echo $this->Form->button($label, ['class' => 'btn btn-outline-primary btn-sm py-0']);
    echo $this->Form->end();
} elseif ($Contacts->isAllowed($condition_id, $user->id, $loginUserGroup)) {
    echo "<button type='button' class='btn btn-outline-dark btn-sm py-0' disabled='disabled'>{$label}済</button>";
} else {
    $contact = $Contacts->find()
            ->where(['condition_id' => $condition_id, 'user_id' => $user->id])
            ->first();;

    echo $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'allow'], 'class' => 'd-inline-block']);
    echo $this->Form->hidden('contact_id', ['value' => $contact->id]);
    echo $this->Form->hidden('callback_url', ['value' => $url]);
    echo $this->Form->button($label, ['class' => 'btn btn-outline-primary btn-sm py-0']);
    echo $this->Form->end();
}
?>