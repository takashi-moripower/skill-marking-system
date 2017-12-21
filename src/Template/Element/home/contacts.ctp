<div class="card my-3">
    <div class="card-header">コンタクト要求</div>
    <table class="table table-bordered m-0">
        <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <th><?= h($contact->condition->title) ?></th>
                    <td><?= h($contact->state) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
