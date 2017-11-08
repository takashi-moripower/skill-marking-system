<?php

/**
 * Description of PathBehavior
 *
 * @author MoripoweDT
 */

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class PathBehavior extends Behavior {

    public function findPathName($query, $options) {
        $separator = Hash::get($options, 'separator', ' > ');
        $table = $this->getTable();


        $query
                ->join([
                    'Path' => [
                        'table' => $table->getTable(),
                        'type' => 'left',
                        'conditions' => [
                            'Path.lft <= ' . $table->aliasField('lft'),
                            'Path.rght >= ' . $table->aliasField('rght'),
                        ]
                    ]
                ])
                ->group($table->aliasField('id'))
                ->select(['path' => "group_concat(Path.name order by Path.lft ASC separator '{$separator}')"])
        ;

        return $query;
    }

    /**
     * 子孫＋自身を検索
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findDescendants($query, $options) {
        $ids = Hash::get($options, 'ids', (array) Hash::get($options, 'id'));
        $table = $this->getTable();

        $descendants = $table->find()
                ->join(['Descendants' => [
                        'table' => $table->getTable(),
                        'type' => 'inner',
                        'conditions' => [
                            'Descendants.lft >=' . $table->aliasField('lft'),
                            'Descendants.rght <=' . $table->aliasField('rght')
                ]]])
                ->where([$table->aliasField('id') . ' IN' => $ids])
                ->select('Descendants.id');
        
        return $query->where([$table->aliasField('id') . ' IN' => $descendants]);
    }

    /**
     * 祖先＋自身を検索
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findAncestors($query, $options) {
        $ids = Hash::get($options, 'ids', (array) Hash::get($options, 'id'));
        $table = $this->getTable();

        $ancestors = $table->find()
                ->join(['Ancestors' => [
                        'table' => $table->getTable(),
                        'type' => 'inner',
                        'conditions' => [
                            'Ancestors.lft <= ' . $table->aliasField('lft'),
                            'Ancestors.rght >= ' . $table->aliasField('rght'),
                ]]])
                ->where([$table->aliasField('id') . ' IN' => $ids])
                ->select('Ancestors.id');

        return $query->where([$table->aliasField('id') . ' IN' => $ancestors]);
    }

}
