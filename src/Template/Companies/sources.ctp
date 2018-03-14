<?php
use Cake\Utility\Hash;
?>
<?= $this->Form->create(null); ?>
<table class="table table-sm table-bordered">
    <thed>
        <th>
            <?= $this->Form->checkbox("check_all", ['value' => 1, 'hiddenField' => false]) ?>
        </th>
        <th class="">名称</th>
        <th class="">所在地</th>
        </thead>
        <tbody>    
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td>
                        <?= $this->Form->checkbox("display[{$company->ID}]", ['value' => 1 , 'checked' => in_array( $company->ID , $company_displaying )]) ?>
                    </td>
                    <td><?php
                        if (!empty($company->url)) {
                            echo $this->Html->link($company->post_title, $company->url, ['target' => '_blank']);
                        } else {
                            echo $company->post_title;
                        }
                        ?></td>
                    <td><?= $company->address ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
</table>
<div class="text-right">
    <button class="btn btn-primary btn-sm">保存</button>
</div>
<?= $this->Form->end() ?>
<?= $this->Element('paginator') ?>

<?php $this->append('script'); ?>
<script>
    $(function () {
        $(document).on('click', 'input[name="check_all"]', function (event) {
            var val = $('input[name="check_all"]').prop('checked');

            $('input[type="checkbox"][name^="display"]').prop('checked', val);
        });

    });
</script>
<?php $this->end() ?>