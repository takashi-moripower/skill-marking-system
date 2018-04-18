<?php
$loginUserId = $this->getLoginUser('id');
?>

<div class="card mt-1">
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody role="skills">
                <tr>
                    <th class="w-20 border-top-0">
                        作者の採点
                    </th>
                    <td class=" border-top-0">
                        <?php
                        echo $this->Element('skills/skills', ['skills' => $skillsBySelf, 'class' => 'bg-skill-owner ']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?= h($this->getLoginUser('name')) ?> 以外の採点</th>
                    <td>
                        <?php
                        echo $this->Element('skills/skills', ['skills' => $skillsByOther, 'class' => '']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?= h($this->getLoginUser('name')) ?> の採点
                        <?= $this->Element('popup_hint', ['message' => 'スキルレベルボタンをクリックすると即座に情報は保存されます']) ?>
                    </th>
                    <td>
                        <?php
                        foreach ($skillsByLoginUser as $skill) {
                            echo $this->Element('works/loginUserMark', compact('skill', 'loginUserId'));
                        }
                        echo $this->Element('works/newMark', compact('loginUserId'));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
