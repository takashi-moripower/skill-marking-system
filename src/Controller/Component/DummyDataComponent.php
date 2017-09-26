<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;
use App\Model\Entity\Organization;
use App\Model\Entity\User;
use App\Model\Entity\Junle;
use App\Model\Entity\Field;
use App\Model\Entity\Skill;
use App\Model\Entity\Work;
use App\Model\Entity\SkillsWork;
use App\Defines\Defines;

class DummyDataComponent extends Component {

    protected function _truncate($table) {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute('TRUNCATE TABLE ' . $table);
        return $results;
    }

    public function createDummyOrganizations() {

        $tableO = TableRegistry::get('Organizations');

        for ($i = 1; $i <= 5; $i++) {
            $newEntity = new Organization([
                'name' => sprintf("組織%02d", $i),
                'parent_id' => NULL,
            ]);
            $tableO->save($newEntity);
        }
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 2; $j++) {
                $newEntity = new Organization([
                    'name' => sprintf("組織%02d 部署%02d", $i, $j),
                    'parent_id' => $i,
                ]);
                $tableO->save($newEntity);
            }
        }
    }

    public function truncateOrganizations() {
        $this->_truncate('organizations');
        $this->_truncate('organizations_users');
    }

    public function createDummyUsers() {
        $tableO = TableRegistry::get('Organizations');

        $tableU = TableRegistry::get('Users');

        $admin = new User([
            'name' => 'admin',
            'group_id' => Defines::GROUP_ADMIN,
            'email' => 'takashi@moripower.jp',
            'password' => '0123'
        ]);

        $r = $tableU->save($admin);
        /*
          echo "<pre>";
          print_r($admin);
          echo "</pre>";
          exit();


          exit();
         */
        $organizations = $tableO->find()
                ->where(['parent_id is' => null]);
        foreach ($organizations as $org) {
            $newEntity = new User([
                'name' => $org->name . '管理者',
                'group_id' => Defines::GROUP_ORGANIZATION_ADMIN,
                'email' => sprintf('group_admin@org%02d.com', $org->id),
                'organizations' => [$org],
                'password' => '0123'
            ]);
            $tableU->save($newEntity);
        }


        $organizations = $tableO->find()
                ->where(['parent_id is not' => null]);

        foreach ($organizations as $org) {
            $newEntity = new User([
                'name' => $org->name . '採点者',
                'group_id' => Defines::GROUP_MARKER,
                'email' => sprintf('marker@org%02d.com', $org->id),
                'organizations' => [$org],
                'password' => '0123'
            ]);

            $tableU->save($newEntity);
        }

        foreach ($organizations as $org) {
            for ($i = 1; $i <= 10; $i++) {
                $newEntity = new User([
                    'name' => $org->name . sprintf('技術者%02d', $i),
                ]);
                $newEntity = new User;
                $newEntity->name = sprintf('組織%02d技術者%02d', $org->id, $i);
                $newEntity->group_id = Defines::GROUP_ENGINEER;
                $newEntity->email = sprintf('engineer%02d@org%02d.com', $i, $org->id);
                $newEntity->organizations = [$org];
                $newEntity->password = '0123';

                $tableU->save($newEntity);
            }
        }
    }

    public function truncateUsers() {
        $this->_truncate('users');
        $this->_truncate('organizations_users');
        $this->_truncate('works');
        $this->_truncate('files');
        $this->_truncate('skills_works');
        $this->_truncate('junles_works');
    }

    public function createDummyJunles() {
        $tableJ = TableRegistry::get('Junles');

        for ($i = 1; $i <= 10; $i++) {
            $newEntity = new Junle;
            $newEntity->name = sprintf('ジャンル%02d', $i);

            $tableJ->save($newEntity);
        }
    }

    public function truncateJunles() {
        $this->_truncate('junlses');
        $this->_truncate('junses_works');
    }

    public function createDummyFields() {
        $tableF = TableRegistry::get('Fields');
        $tableO = TableRegistry::get('Organizations');

        $fields = \App\Defines\Dummies::Fields;
        $tableF->createFromArray($fields);

        $orgs = $tableO->find()
                ->where(['parent_id is' => null]);
        foreach ($orgs as $org) {
            $newEntity = new Field([
                'name' => sprintf('%s 内部評価用', $org->name),
                'parent_id' => null,
                'organization_id' => $org->id,
            ]);

            $tableF->save($newEntity);
        }
    }

    public function truncateFields() {
        $this->_truncate('fields');
        $this->_truncate('skills');
        $this->_truncate('skills_works');
    }

    public function createDummySkills() {
        $tableS = TableRegistry::get('skills');

        $skills = \App\Defines\Dummies::Skills;

        $tableS->createFromArray($skills);

        $tableO = TableRegistry::get('organizations');
        $tableF = TableRegistry::get('fields');

        $orgs = $tableO->find()
                ->where(['parent_id is' => null]);

        foreach ($orgs as $org) {
            $field = $tableF->find()
                    ->where(['name like' => "%{$org->name}%"])
                    ->first();

            $fieldId = Hash::get($field, 'id', null);

            if ($fieldId) {
                $newSkill = new Skill([
                    'name' => "{$org->name} 内部評価",
                    'field_id' => $fieldId
                ]);
                $tableS->save($newSkill);
            }
        }
    }

    public function truncateSkills() {
        $this->_truncate('skills');
        $this->_truncate('skills_works');
    }

    public function createDummyWorks() {
        $tableU = TableRegistry::get('users');
        $tableW = TableRegistry::get('works');

        $engineers = $tableU->find()
                ->where(['group_id' => Defines::GROUP_ENGINEER])
                ->toArray();

        for ($i = 1; $i <= 1000; $i++) {
            $engineer = $engineers[rand(0, count($engineers) - 1)];

            $newWork = new Work([
                'name' => sprintf('作品%04d', $i),
                'user_id' => $engineer->id,
                'junles' => $this->_getRandomJunles(),
                'note' => $this->_getRandomNote(),
            ]);

            $tableW->save($newWork);
        }
    }

    protected function _getRandomJunles() {
        $junles = $this->_getAllJunles();
        $result = [];
        while (1) {
            $newJunle = $junles[rand(0, count($junles) - 1)];

            if (in_array($newJunle, $result)) {
                return $result;
            }

            array_push($result, $newJunle);

            if (rand(0, 1)) {
                return $result;
            }
        }
    }

    protected function _getRandomNote() {
        $note = '';

        $text1 = [
            '森永司氏',
            'プーチン大統領',
            '北野武氏',
            'ビル・ゲイツ氏',
        ];
        $text2 = [
            '絶賛!!',
            '推薦',
            '激怒!!',
            '完全無視!!',
        ];
        $text3 = [
            '構想1秒、制作2秒！',
            '構想10年、制作5年！',
            '予算総額10000ドル！',
            '予算総額1ドル！',
            'アカデミー賞ノミネート直前！',
            'Youtubeで大ブレイクの予感！',
            'テストプレイで失神者続出！',
            '80%のお客様が　弊社従来製品より「よく落ちる」と回答',
        ];

        

        return $note;
    }

    protected function _getAllJunles() {
        if (!isset($this->_junles)) {

            $tableJ = TableRegistry::get('junles');
            $this->_junles = $tableJ->find()
                    ->toArray();
        }

        return $this->_junles;
    }

    public function truncateWorks() {
        $this->_truncate('works');
        $this->_truncate('files');
        $this->_truncate('junles_works');
        $this->_truncate('skills_works');
    }

    public function createDummyMarks() {
        $tableU = TableRegistry::get('Users');
        $tableS = TableRegistry::get('Skills');
        $works = TableRegistry::get('works')
                ->find();


        $data = [];
        foreach ($works as $work) {
            $orgs = $tableU->getOrganizations($work->user_id);

            $markers = $tableU->find()
                    ->where(['group_id' => Defines::GROUP_MARKER])
                    ->find('members', ['organization_id' => $orgs])
                    ->select('id');
            foreach ($markers as $marker) {
                $skills = $tableS
                        ->find('usable', ['user_id' => [$marker->id, $work->user_id]])
                        ->toArray();

                $skillIds = Hash::extract($skills, '{n}.id');
                $this->_setRandomMarks($skillIds, $work->id, $marker->id);
            }
        }

    }

    protected function _setRandomMarks($skillIds, $work_id, $marker_id) {
        $tableSW = TableRegistry::get('skills_works');
        while (1) {
            $param = [
                'skill_id' => $skillIds[rand(0, count($skillIds) - 1)],
                'work_id' => $work_id,
                'user_id' => $marker_id,
            ];

            if ($tableSW->exists($param)) {
                return;
            }

            $newEntity = new SkillsWork($param);
            $newEntity->level = rand(1, Defines::SKILL_LEVEL_MAX);
            $tableSW->save($newEntity);

            if (rand(0, 1)) {
                return;
            }
        }
    }

    public function truncateMarks() {
        $this->_truncate('skills_works');
    }

}
