<?php

/**
 * Description of DepthBehavior
 *
 * @author MoripoweDT
 */

namespace App\Model\Behavior;

use Cake\ORM\Behavior;

class DepthBehavior extends Behavior {


    public function findDepth($query, $options) {
        
        $table = $this->getTable();

        $query->select($table);
        $query->join([
            'table' => $table->getTable(),
            'alias' => 'parents',
            'type' => 'LEFT',
            'conditions' => 'parents.lft < ' . $table->aliasField('lft') . ' and parents.rght > ' . $table->aliasField('rght'),
        ]);
        $query->select(['depth' => 'count(parents.id)']);
        $query->group($table->aliasField('id'));

        return $query;
    }

}
