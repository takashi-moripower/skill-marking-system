<table class="table table-bordered">
    <tbody>
        <tr>
            <th>name</th>
            <td><?= h($user->name); ?></td>
        </tr>
        <?php foreach( $user->works as $work ): ?>
        <tr>
            <th><?=h($work->name)?></th>
            <td><?= $this->Element('skills',['skills'=>$work->skills]); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
