<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Files Model
 *
 * @property \App\Model\Table\WorksTable|\Cake\ORM\Association\BelongsTo $Works
 *
 * @method \App\Model\Entity\File get($primaryKey, $options = [])
 * @method \App\Model\Entity\File newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\File[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\File|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\File patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\File[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\File findOrCreate($search, callable $callback = null, $options = [])
 */
class FilesTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('files');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Works', [
            'foreignKey' => 'work_id',
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
                ->scalar('name')
                ->allowEmpty('name');

        $validator
                ->allowEmpty('contents');

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
        $rules->add($rules->existsIn(['work_id'], 'Works'));

        return $rules;
    }

    public function beforeSave($event, $entity, $options) {
        if ($entity->error === UPLOAD_ERR_OK) {
            
        } else {
            return false;
        }
    }

    public function afterSave($event, $entity, $options) {
        if ($entity->error === UPLOAD_ERR_OK && $entity->isNew() ) {
            $root = \Cake\Core\Configure::read('App.wwwRoot');
            $upload = $root . 'uploads/';
            $tmp_dir = ini_get('upload_tmp_dir');

            $newTemp = $upload . sprintf('%06d_', $entity->id).ltrim($entity->tmp_name, $tmp_dir);
            
            copy( $entity->tmp_name , $newTemp );
            
            $entity->tmp_name = $newTemp;
            $entity->isNew(false);
            $this->save($entity);
        }
    }
    
    public function afterDelete( $event , $entity , $options ){
        unlink( $entity->tmp_name );
    }

    protected function _getContents($entity) {
        $ret = file_get_contents($entity->tmp_name);
        if ($ret === false) {
            throw new RuntimeException('Can not get thumbnail image.');
        }

        return $ret;
    }

}
