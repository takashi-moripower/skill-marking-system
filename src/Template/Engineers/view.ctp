<table class="table table-bordered">
    <tbody>
        <tr>
            <th class="w-25">名称</th>
            <td><?= h($user->name); ?></td>
        </tr>
        <tr>
            <th>所属</th>
            <td>
                <?php foreach ($user->organizations as $org): ?>
                    <?= $org->name ?>
                    <?= ($org !== end($user->organizations)) ? ',' : '' ?>
                <?php endforeach ?>
            </td>
        </tr>
        <?php foreach ($user->works as $work): ?>
            <tr>
                <th><?= $this->Html->link(h($work->name), ['controller' => 'works', 'action' => 'view', $work->id]) ?></th>
                <td><?= $this->Element('skills', ['skills' => $work->skills]); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
