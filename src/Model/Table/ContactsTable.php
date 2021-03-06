<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Contacts Model
 *
 * @property \App\Model\Table\ConditionsTable|\Cake\ORM\Association\BelongsTo $Conditions
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Contact get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Contact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contact findOrCreate($search, callable $callback = null, $options = [])
 */
class ContactsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->addBehavior('Search.Search');

        $this->setTable('contacts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Conditions', [
            'foreignKey' => 'condition_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
                ->integer('flags')
                ->requirePresence('flags', 'create')
                ->notEmpty('flags');

        $validator
                ->dateTime('engineer_date')
                ->allowEmpty('engineer_date');

        $validator
                ->dateTime('company_date')
                ->allowEmpty('company_date');

        $validator
                ->dateTime('teacher_date')
                ->allowEmpty('teacher_date');

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
        $rules->add($rules->existsIn(['condition_id'], 'Conditions'));
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
                ->finder('states', ['alwaysRun' => true])
                ->like('keyword', ['field' => ['Users.name','Conditions.title'], 'before' => true, 'after' => true])
;
        return $searchManager;
    }

    /**
     * 該当コンタクトが存在するかどうか
     * @param type $condition_id
     * @param type $user_id
     * @return type
     */
    public function isExists($condition_id, $user_id) {
        $count = $this->find()
                ->where([
                    'user_id' => $user_id,
                    'condition_id' => $condition_id
                ])
                ->count();

        return ( $count != 0 );
    }

    /**
     * 該当コンタクトに許可を与えているかどうか
     */
    public function isAllowed($condition_id, $user_id, $login_user_group) {
        $contact = $this->find()
                ->where(['condition_id' => $condition_id, 'user_id' => $user_id])
                ->first();

        $state = $contact->getState($login_user_group);

        return ( $state == Defines::CONTACT_STATE_ALLOW );
    }

    /**
     * あるユーザーが閲覧できるcontactを返す
     * @param type $query
     * @param type $options
     */
    public function findVisible($query, $options) {
        $user_id = Hash::get($options, 'user_id');
        $group_id = Hash::get($options, 'group_id');
        if (empty($group_id)) {
            $group_id = $this->Users->get($user_id, ['fields' => ['group_id']])->group_id;
        }

        switch ($group_id) {
            case Defines::GROUP_ADMIN:
                return $query;

            case Defines::GROUP_ORGANIZATION_ADMIN:
                $students = $this->Users->find('students', ['teacher_id' => $user_id])
                        ->select('id');
                $query->where([$this->aliasField('user_id') . ' IN' => $students]);
                return $query;

            case Defines::GROUP_MARKER:
                $conditions = $this->Conditions->find()
                        ->select('id')
                        ->where([$this->Conditions->aliasField('user_id') => $user_id])
                ;
                $query->where([$this->aliasField('condition_id') . ' IN' => $conditions]);
                return $query;

            case Defines::GROUP_ENGINEER:
                $query
                        ->where([$this->aliasField('user_id') => $user_id]);
        }

        return $query;
    }

    public function findStates($query, $options) {

        $setWhereFunc = function($field, $key, $flag_allow, $flag_deny) use( $query, $options ) {
            $flag = "({$field} & ".($flag_allow | $flag_deny).") = ";

            
            $var = Hash::get($options, $key );
            switch ($var) {
                case null:
                    break;
                
                case Defines::CONTACT_STATE_UNDEFINED:
                    return $query->where([$flag => 0]);

                case Defines::CONTACT_STATE_ALLOW:
                    return $query->where([$flag => $flag_allow]);

                case Defines::CONTACT_STATE_DENY:
                    return $query->where([$flag => $flag_deny]);
                    
            }

            return $query;
        };

        $query = $setWhereFunc($this->aliasField('flags'), 'search.state_engineer', Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER, Defines::CONTACT_FLAG_DENIED_BY_ENGINEER);
        $query = $setWhereFunc($this->aliasField('flags'), 'search.state_teacher', Defines::CONTACT_FLAG_ALLOW_BY_TEACHER, Defines::CONTACT_FLAG_DENIED_BY_TEACHER);
        $query = $setWhereFunc($this->aliasField('flags'), 'search.state_company', Defines::CONTACT_FLAG_ALLOW_BY_COMPANY, Defines::CONTACT_FLAG_DENIED_BY_COMPANY);


        return $query;
    }

}
