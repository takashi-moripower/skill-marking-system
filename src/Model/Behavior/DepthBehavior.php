<?php

/**
 * Description of DepthBehavior
 *
 * @author MoripoweDT
 */

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\TableRegistry;

class DepthBehavior extends Behavior {

    public function findDepth($query, $options) {

        $table = $this->getTable();
        $subTable = TableRegistry::get('Parents', ['table' => $table->getTable()]);

        $subquery = $subTable->find()
                ->where(['Parents.lft < ' . $table->aliasField('lft')])
                ->where(['Parents.rght > ' . $table->aliasField('rght')])
                ->select(['depth' => 'count(Parents.id)']);

        $query->select(['depth' => $subquery]);

        return $query;
    }

    public function findRoot($query, $options) {
        $table = $this->getTable();

        $subQuery = $table->find()
                ->join(['Parents' =>
                    ['type' => 'left', 'table' => $table->getTable(), 'conditions' => ['Parents.lft < ' . $table->aliasField('lft'), 'Parents.rght > ' . $table->aliasField('rght')]]
                ])
                ->group($table->aliasField('id'))
                ->having(['count(Parents.id)=0'])
                ->select($table->aliasField('id'));
        ;
        $query->where([$table->aliasField('id')." IN"=>$subQuery]);

        return $query;
    }

}
