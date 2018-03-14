<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use DateTime;
use App\Defines\Defines;
use App\Utility\MyUtil;
/**
 * RegisteringUsers Model
 *
 * @method \App\Model\Entity\RegisteringUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\RegisteringUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RegisteringUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RegisteringUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RegisteringUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RegisteringUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RegisteringUser findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RegisteringUsersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('registering_users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Organizations');
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

        $validator
                ->email('email')
                ->requirePresence('email', 'create')
                ->notEmpty('email');

        $validator
                ->scalar('token')
                ->requirePresence('token', 'create')
                ->notEmpty('token');

        $validator
                ->scalar('organization_id')
                ->requirePresence('token', 'create')
                ->notEmpty('organization_id');

        $validator
                ->scalar('graduation_year')
                ->requirePresence('token', 'create')
                ->notEmpty('graduation_year');

// プロバイダーではないコールバック関数を利用する
        $validator->add('email', 'custom', [
            'rule' => [$this, 'emailInUser'],
        ]);
        return $validator;
    }

    public function emailInUser($value, $context) {

        $Users = TableRegistry::get('Users');
        $count = $Users->find()
                ->where(['email' => $value])
                ->count();

        if ($count != 0) {
            return 'そのEmailアドレスは既に登録されています';
        }
        return true;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        return $rules;
    }

    public function afterSave($event, $entity, $options) {
        $this->_clean();
        return true;
    }

    protected function _clean() {
        $dateLimit = new DateTime;

        $dateLimit->modify('-' . Defines::USER_REGISTRY_TOKEN_LIMIT . 'minute');

        $this->query()
                ->where(['created <' => $dateLimit, 'valid_email' => 0])
                ->delete()
                ->execute();
    }

    public function admit($entity) {
        $Users = TableRegistry::get('Users');

        $userData = [
            'name' => $entity->name,
            'password' => MyUtil::makeRandStr(8),
            'email' => $entity->email,
            'group_id' => Defines::GROUP_ENGINEER,
            'organizations' => [
                '_ids' => [$entity->organization_id]
            ],
            'engineer' => [
                'graduation_year' => $entity->graduation_year,
                'code' => self::getNewCode($entity->organization_id, $entity->graduation_year)
            ]
        ];

        $newUser = $Users->newEntity($userData);

        if (!$Users->save($newUser)) {
            return false;
        }

        $this->delete($entity);

        return $userData;
    }

    public static function getNewCode($organization_id, $graduation_year) {
        $organization = TableRegistry::get('Organizations')->get($organization_id);
        $Engineers = TableRegistry::get('Engineers');
        do {
            $code = $organization->code_prefix . sprintf("%04d%04d", $graduation_year, rand(0, 9999));

            $isExists = $Engineers->find()
                    ->where(['code' => $code])
                    ->limit(1)
                    ->count('id');
        } while ($isExists);


        return $code;
    }

}
