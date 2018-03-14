<?php

use App\Defines\Defines;

$loginUserGroup = $this->getLoginUser('group_id');
?>

<?php if ($loginUserGroup == Defines::GROUP_ADMIN): ?>
    <div class="text-right my-2">
        <?= $this->Html->link('表示選択', ['controller' => 'companies', 'action' => 'sources'], ['class' => 'btn btn-sm btn-primary']) ?>
    </div>
<?php endif; ?>
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th class="">名称</th>
            <th class="">所在地</th>
        </tr>
    </thead>
    <tbody>    
        <?php foreach ($companies as $company): ?>
            <tr>
                <td class="company-logo">
                    <?php
                    $label = $company->post_title;
                    if ($company->logo) {
                        $label = "<img src='{$company->logo}' alt='{$company->post_title}'/>" . $label;
                    }

                    if ($company->url) {
                        echo $this->Html->link($label, $company->url, ['escape' => false]);
                    } else {
                        echo $label;
                    }
                    ?>
                </td>
                <td><?= $company->address ?></td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->Element('paginator') ?>
