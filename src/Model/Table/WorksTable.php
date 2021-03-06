<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Session;
use App\Defines\Defines;
use Cake\Utility\Hash;

/**
 * Works Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FilesTable|\Cake\ORM\Association\HasMany $Files
 * @property \App\Model\Table\JunlesTable|\Cake\ORM\Association\BelongsToMany $Junles
 * @property \App\Model\Table\SkillsTable|\Cake\ORM\Association\BelongsToMany $Skills
 *
 * @method \App\Model\Entity\Work get($primaryKey, $options = [])
 * @method \App\Model\Entity\Work newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Work[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Work|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Work patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Work[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Work findOrCreate($search, callable $callback = null, $options = [])
 */
class WorksTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('works');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'fields' => ['id', 'name'],
        ]);

        $this->hasMany('Files', [
            'foreignKey' => 'work_id',
        ]);

        $this->hasMany('Comments', [
            'foreignKey' => 'work_id',
        ]);

        $this->belongsToMany('Junles', [
            'foreignKey' => 'work_id',
            'targetForeignKey' => 'junle_id',
            'joinTable' => 'junles_works'
        ]);
        $this->belongsToMany('Skills', [
            'foreignKey' => 'work_id',
            'targetForeignKey' => 'skill_id',
            'joinTable' => 'skills_works'
        ]);
        $this->addBehavior('Search.Search');
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
                ->notEmpty('name');

        $validator
                ->scalar('note')
                ->allowEmpty('note');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * @return \Search\Manager
     */
    public function searchManager() {
        /** @var \Search\Manager $searchManager */
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
                ->finder('organization_id', ['finder' => 'Organization'])
                ->finder('junle_id', ['finder' => 'Junle'])
                ->finder('mark-state', ['finder' => 'MarkState'])
                ->like('keyword', ['field' => ['name', 'note', 'Users.name'], 'before' => true, 'after' => true])
        ;

        return $searchManager;
    }

    /**
     * 絞り込み検索用　製作者の所属組織
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findOrganization($query, $options) {
        $org_ids = Hash::get($options, 'organization_ids', [Hash::get($options, 'organization_id')]);

        $users = TableRegistry::get('OrganizationsUsers')->find()
                ->where(['organization_id IN' => $org_ids])
                ->group('user_id')
                ->select('user_id');

        $query->where([$this->aliasField('user_id') . ' IN' => $users]);

        return $query;
    }

    /**
     * 絞り込み検索用　所属ジャンル
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findJunle($query, $options) {
        $junle_id = $options['junle_id'];
        $tableJW = TableRegistry::get('junles_works');
        $subquery = $tableJW->find()
                ->select('id')
                ->where([$tableJW->aliasField('junle_id') => $junle_id, $tableJW->aliasField('work_id') . ' = ' . $this->aliasField('id')]);

        $query
                ->where(function($exp, $q) use ($subquery) {
                    return $exp->exists($subquery);
                });


        return $query;
    }

    /**
     * 絞り込み検索用　採点済みか否か
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findMarkState($query, $options) {
        $state = Hash::get($options, 'mark-state', Defines::MARK_STATE_ALL);

        if ($state == Defines::MARK_STATE_MARKED) {
            $query->find('mark');
            return $query->where(['Marks.mark' => 1]);
        }

        if ($state == Defines::MARK_STATE_UNMARKED) {
            $query->find('mark');
            return $query->where(['Marks.mark' => 0]);
        }

        return $query;
    }

    public function beforeSave($event, $entity, $options) {
        if (!empty($entity->files)) {
            foreach ($entity->files as $id => $file) {
                if (empty($file->id) && empty($file->tmp_name)) {
                    unset($entity->files[$id]);
                }
            }
        }

        if (!empty($entity->file_to_remove)) {
            /*
              $this->Files->query()
              ->delete()
              ->where(['id IN' => $entity->file_to_remove])
              ->execute();
             */
            foreach ($entity->file_to_remove as $fileId) {
                $file = $this->Files->get($fileId);
                $this->Files->delete($file);
            }
        }
    }

    public function findUser($query, $options) {
        //技術者は自分の作品のみ閲覧可能
        if ($options['group_id'] == Defines::GROUP_ENGINEER) {
            return $query
                            ->where(['user_id' => $options['user_id']]);
        }

        //組織管理者、採点者は　自分の関連組織　および子組織に所属するユーザーの作品を閲覧可能
        if ($options['group_id'] == Defines::GROUP_ORGANIZATION_ADMIN || $options['group_id'] == Defines::GROUP_MARKER) {
            $tableOrg = TableRegistry::get('organizations');
            $orgIds = $tableOrg->find('user', ['user_id' => $options['user_id'], 'relation' => 'children'])
                    ->select('id');

            $query->find('Organization', ['organization_ids' => $orgIds]);
            return $query;
        }

        return $query;
    }

    public function findMark($query, $options) {
        $session = new Session();
        $loginUserId = $session->read('Auth.User.id');
        $userId = Hash::get($options, 'user_id', $loginUserId);

        $subquery = TableRegistry::get('SW', ['table' => 'skills_works'])
                ->find()
                ->select('id')
                ->where(function($exp, $q) {
                    return $exp->equalFields('SW.work_id', 'Marks.id');
                })
                ->where(['SW.user_id' => $userId]);


        $marks = TableRegistry::get('Marks', ['table' => 'works'])
                ->find()
                ->select(['id' => 'id'])
                ->select(function($q) use ($subquery) {
            return ['mark' => $q->newExpr()->exists($subquery)];
        });

        $query->join(['Marks' => [
                        'table' => $marks,
                        'type' => 'left',
                        'conditions' => [
                            'Marks.id = ' . $this->aliasField('id')
                        ]]
                ])
                ->select(['mark' => 'Marks.mark']);




        return $query;
    }

}
