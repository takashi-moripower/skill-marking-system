<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Junles Model
 *
 * @property \App\Model\Table\WorksTable|\Cake\ORM\Association\BelongsToMany $Works
 *
 * @method \App\Model\Entity\Junle get($primaryKey, $options = [])
 * @method \App\Model\Entity\Junle newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Junle[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Junle|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Junle patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Junle[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Junle findOrCreate($search, callable $callback = null, $options = [])
 */
class JunlesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('junles');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Works', [
            'foreignKey' => 'junle_id',
            'targetForeignKey' => 'work_id',
            'joinTable' => 'junles_works'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->allowEmpty('name');

        return $validator;
    }
}
