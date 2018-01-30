<?php

use App\Model\Entity\Skill;
use App\Utility\MyUtil;
use Cake\Utility\Hash;

$skills = Hash::sort(Skill::findByMarker($user->skills, $user->id, true), '{n}.field.lft');

$counts = MyUtil::countSkills($skills);

$org_selectors = [];
foreach ($user->organizations as $organization) {
    $org_selectors[$organization->id] = $organization->path_name;
}
?>

<h3 class="mt-5">スキル評価　および　集団内での偏差値</h3>
<table class="table table-bordered table-sm table-deviation">
    <thead>
        <tr>
            <th>スキル名</th>
            <th>レベル</th>
            <th>評価数</th>
            <th class="w-20"><?= $this->Form->select('org_id', $org_selectors); ?></th>
            <th class="w-20">全組織</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($counts as $skill_id => $levels): ?>
            <?php foreach ($levels as $level => $count): ?>
                <tr>
                    <?php if ($level === MyUtil::first_key($levels)): ?>
                        <th rowspan="<?= count($levels) ?>"><?= Hash::extract($skills, "{n}[id={$skill_id}]")[0]->label ?></th>
                    <?php endif; ?>
                    <td class="text-right"><?= $level ?></td>
                    <td class="text-right"><?= $count ?></td>
                    <td class="text-right">
                        <?php foreach ($user->organizations as $organization): ?>
                            <div org_id="<?= $organization->id ?>">
                                <?= number_format(MyUtil::getDeviation($skill_id, $organization->id, $level), 2) ?>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    <td class="text-right"><?= number_format(MyUtil::getDeviation($skill_id, 0, $level), 2) ?></td>
                </tr>
            <?php endforeach ?>
        <?php endforeach ?>
    </tbody>
</table>


<?php $this->append('script') ?>
<script>
    $(function () {

        updateOrg();
        
        $('select[name="org_id"]').on('change',updateOrg);


        function updateOrg() {
            var org_id = $('select[name="org_id"]').val();
            $('.table-deviation div[org_id=' + org_id + ']').show();
            $('.table-deviation div[org_id!=' + org_id + ']').hide();
        }
    });
</script>
<?php $this->end() ?>