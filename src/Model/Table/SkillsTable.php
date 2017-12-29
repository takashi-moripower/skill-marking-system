<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Model\Entity\Skill;
use App\Defines\Defines;

/**
 * Skills Model
 *
 * @property \App\Model\Table\FieldsTable|\Cake\ORM\Association\BelongsTo $Fields
 * @property \App\Model\Table\WorksTable|\Cake\ORM\Association\BelongsToMany $Works
 *
 * @method \App\Model\Entity\Skill get($primaryKey, $options = [])
 * @method \App\Model\Entity\Skill newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Skill[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Skill|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Skill patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Skill[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Skill findOrCreate($search, callable $callback = null, $options = [])
 */
class SkillsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->addBehavior('Search.Search');

        $this->setTable('skills');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Fields', [
            'foreignKey' => 'field_id',
            'joinType' => 'INNER',
        ]);


        $this->belongsToMany('Works', [
            'foreignKey' => 'skill_id',
            'targetForeignKey' => 'work_id',
            'joinTable' => 'skills_works'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->scalar('name')
                ->requirePresence('name', 'create')
                ->notEmpty('name');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['field_id'], 'Fields'));

        return $rules;
    }

    /**
     * @return \Search\Manager
     */
    public function searchManager() {
        /** @var \Search\Manager $searchManager */
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
                ->finder('organization_id', ['finder' => 'ByOrganization'])
                ->finder('field_id', ['finder' => 'ByField'])
        ;


        return $searchManager;
    }

    public function createFromArray($src) {
        foreach ($src as $fieldName => $skills) {
            $field = $this->Fields->find()
                    ->where(['name' => $fieldName])
                    ->first();

            $fieldId = Hash::get($field, 'id', null);
            foreach ($skills as $skillName) {
                $newEntity = new Skill;
                $newEntity->name = $skillName;
                $newEntity->field_id = $fieldId;

                $this->save($newEntity);
            }
        }
    }

    /**
     * あるユーザーが利用可能なスキルを取得　配列を渡された場合はAND
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findUsable($query, $options) {
        if (isset($options['group_id']) && $options['group_id'] == Defines::GROUP_ADMIN) {
            return $query;
        }

        if (isset($options['user_id'])) {
            $user_id = $options['user_id'];

            $fields = $this->Fields->find('usable', ['user_id' => $user_id])
                    ->select('id');

            $query->where([$this->aliasField('field_id') . ' IN' => $fields]);
            return $query;
        }

        if (isset($options['user_ids'])) {
            $user_ids = $options['user_ids'];

            $cases = [];
            foreach ($user_ids as $user_id) {
                $fields = $this->Fields->find('usable', ['user_id' => $user_id])
                        ->select('id');

                $cases[] = [$this->aliasField('field_id') . ' IN' => $fields];
            }


            $query->where(['and' => $cases]);

            return $query;
        }
    }

    /**
     * あるユーザーが編集可能な
     * @param type $query
     * @param type $options
     */
    public function findEditable($query, $options) {
        $user_id = Hash::get($options, 'user_id');
        $fields = TableRegistry::get('Fields')
                ->find('editable', ['user_id' => $user_id])
                ->select('id');

        $query->where(['field_id IN' => $fields]);

        return $query;
    }

    /**
     * editable というパラメータを各Entityにセット
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findSetEditable($query, $options) {
        $user_id = Hash::get($options, 'user_id');

        $skillsEditable = TableRegistry::get('SkillsEditable', ['table' => 'skills', 'className' => '\App\Model\Table\SkillsTable'])
                ->find('editable', ['user_id' => $user_id])
                ->select('id');

        $query
                ->select(['editable' => $query->newExpr()->addCase([
                        $query->newExpr()->add(['Skills.id IN' => $skillsEditable]),
                        1,
                        'boolean'
                    ])
        ]);
        return $query;
    }

    /**
     * ある技術者に対する評価を返す
     * @param type $query
     * @param type $options
     */
    public function findByEngineer($query, $options) {
        $engineer_id = Hash::get($options, 'engineer_id', Hash::get($options, 'user_id'));

        $tableW = TableRegistry::get('works');
        $tableSW = TableRegistry::get('skills_works');


        $query
                ->leftJoin($tableSW->getAlias(), [$tableSW->aliasField('skill_id') . ' = ' . $this->aliasField('id')])
                ->leftJoin($tableW->getAlias(), [$tableSW->aliasField('work_id') . ' = ' . $tableW->aliasField('id')])
                ->select($this)
                ->select(['engineer_id' => $tableW->aliasField('user_id')])
                ->select(['marker_id' => $tableSW->aliasField('user_id')])
                ->select(['level' => $tableSW->aliasField('level')])
                ->where([$tableW->aliasField('user_id') => $engineer_id])
        ;
        return $query;
    }

    public function findFieldPath($query, $options) {
        $tableF = TableRegistry::get('Fields');

        $fieldPath = $tableF->find('PathName')
                ->select(['path_id' => $tableF->aliasField('id')]);

        $query
                ->join([
                    'path' => [
                        'table' => $fieldPath,
                        'type' => 'left',
                        'conditions' => [
                            $this->aliasField('field_id') . ' = path_id'
                        ]
                    ]
                ])
                ->select(['field_path' => 'path.path']);

        return $query;
    }

    //特定組織および子組織の管理下にあるスキルを取得
    public function findByOrganization($query, $options) {
        $organization_id = Hash::get($options, 'organization_id');
        $orgs = TableRegistry::get('Organizations')->find('descendants', ['id' => $organization_id])
                ->select('Organizations.id');

        $fields = TableRegistry::get('Fields')
                ->find()
                ->where(['Fields.organization_id IN' => $orgs])
                ->select('Fields.id');

        return $query->where([$this->aliasField('field_id') . ' IN' => $fields]);
    }

    //特定フィールドおよびその子孫フィールドに含まれるスキルを取得
    public function findByField($query, $options) {
        $field_id = Hash::get($options, 'field_id');

        $fields = TableRegistry::get('Fields')
                ->find('Descendants', ['id' => $field_id])
                ->select('Fields.id');

        return $query->where([$this->aliasField('field_id') . ' IN' => $fields]);
    }

    public static function toPathList($skills) {
        $skills->find('fieldPath')
                ->contain(['Fields' => ['fields' => []]])
                ->order('Fields.lft')
                ->select(['id', 'name']);

        $list = [];
        foreach ($skills as $skill) {
            $list[$skill->id] = $skill->label;
        }
        return $list;
    }

    static function getSkillLevels() {
        return array_combine(range(1, Defines::SKILL_LEVEL_MAX), range(1, Defines::SKILL_LEVEL_MAX));
    }

    /*
     * [3,4] を　12　に
     */

    static function array2flags($levelsArray) {
        $result = 0;
        foreach ($levelsArray as $l) {
            $result += pow(2, $l - 1);
        }

        return $result;
    }

    /*
     * 12  を　[3,4]に
     */

    static function flags2Array($levelsFlags) {
        return array_filter(self::getSkillLevels(), function($l) use($levelsFlags) {
            return (1 << ($l - 1)) & $levelsFlags;
        });
    }

}
