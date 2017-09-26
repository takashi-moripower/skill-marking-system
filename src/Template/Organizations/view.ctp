<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Organization $organization
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Organization'), ['action' => 'edit', $organization->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Organization'), ['action' => 'delete', $organization->id], ['confirm' => __('Are you sure you want to delete # {0}?', $organization->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Organizations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Organizations'), ['controller' => 'Organizations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Organization'), ['controller' => 'Organizations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Fields'), ['controller' => 'Fields', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Field'), ['controller' => 'Fields', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Organizations Users'), ['controller' => 'OrganizationsUsers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organizations User'), ['controller' => 'OrganizationsUsers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="organizations view large-9 medium-8 columns content">
    <h3><?= h($organization->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($organization->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Parent Organization') ?></th>
            <td><?= $organization->has('parent_organization') ? $this->Html->link($organization->parent_organization->name, ['controller' => 'Organizations', 'action' => 'view', $organization->parent_organization->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($organization->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lft') ?></th>
            <td><?= $this->Number->format($organization->lft) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rght') ?></th>
            <td><?= $this->Number->format($organization->rght) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Fields') ?></h4>
        <?php if (!empty($organization->fields)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Organization Id') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($organization->fields as $fields): ?>
            <tr>
                <td><?= h($fields->id) ?></td>
                <td><?= h($fields->name) ?></td>
                <td><?= h($fields->organization_id) ?></td>
                <td><?= h($fields->parent_id) ?></td>
                <td><?= h($fields->lft) ?></td>
                <td><?= h($fields->rght) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Fields', 'action' => 'view', $fields->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Fields', 'action' => 'edit', $fields->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Fields', 'action' => 'delete', $fields->id], ['confirm' => __('Are you sure you want to delete # {0}?', $fields->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Organizations') ?></h4>
        <?php if (!empty($organization->child_organizations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($organization->child_organizations as $childOrganizations): ?>
            <tr>
                <td><?= h($childOrganizations->id) ?></td>
                <td><?= h($childOrganizations->name) ?></td>
                <td><?= h($childOrganizations->parent_id) ?></td>
                <td><?= h($childOrganizations->lft) ?></td>
                <td><?= h($childOrganizations->rght) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Organizations', 'action' => 'view', $childOrganizations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Organizations', 'action' => 'edit', $childOrganizations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Organizations', 'action' => 'delete', $childOrganizations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childOrganizations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Organizations Users') ?></h4>
        <?php if (!empty($organization->organizations_users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Organization Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($organization->organizations_users as $organizationsUsers): ?>
            <tr>
                <td><?= h($organizationsUsers->id) ?></td>
                <td><?= h($organizationsUsers->user_id) ?></td>
                <td><?= h($organizationsUsers->organization_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'OrganizationsUsers', 'action' => 'view', $organizationsUsers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'OrganizationsUsers', 'action' => 'edit', $organizationsUsers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrganizationsUsers', 'action' => 'delete', $organizationsUsers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $organizationsUsers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($organization->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Group Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Google') ?></th>
                <th scope="col"><?= __('Facebook') ?></th>
                <th scope="col"><?= __('Twitter') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($organization->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->group_id) ?></td>
                <td><?= h($users->name) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->google) ?></td>
                <td><?= h($users->facebook) ?></td>
                <td><?= h($users->twitter) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
