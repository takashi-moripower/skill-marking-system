<table class="table table-bordered">
    <thead>
        <tr>
            <th>name</th>
            <th>count</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($groups as $group): ?>
            <tr>
                <td><a href="<?= $this->Url->build(['action' => 'edit', $group->id]) ?>"><?= $group->name ?></td>
                <td><?= $group->count ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>