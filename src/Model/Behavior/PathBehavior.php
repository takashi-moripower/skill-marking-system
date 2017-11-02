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

        $tableParents = TableRegistry::get('Parents', ['table' => $table->getTable()]);
        $parents = $tableParents->find()
                ->select(['parents_name' => 'name', 'parents_lft' => 'lft', 'parents_rght' => 'rght'])
                ->order(['lft' => 'ASC'])
        ;


        $tablePath = TableRegistry::get('Path', ['table' => $table->getTable()]);
        $path = $tablePath->find()
                ->select(['path_id' => 'Path.id'])
                ->select(['path_name' => "group_concat(parents_name separator '{$separator}')"])
                ->join([
                    'Parents' => [
                        'table' => $parents,
                        'type' => 'left',
                        'conditions' => [
                            'parents_lft <= Path.lft',
                            'parents_rght >= Path.lft'
                        ]
                    ]
                ])
                ->group('path_id')
        ;


        $query
                ->join([
                    'Path' => [
                        'table' => $path,
                        'type' => 'left',
                        'conditions' => [
                            'Path.path_id = ' . $table->aliasField('id')
                        ]
                    ]
                ])
                ->select(['path' => 'path_name'])
        ;
        return $query;
    }

}

/*
select * 
from fields as fields
left join (
	select path.id , group_concat(parents.name) as path_name
	from fields as path
	left join (
		select * from fields
		order by lft ASC
	) as parents on path.lft >= parents.lft and path.rght <= parents.rght
	group by path.id
) as path
on path.id = fields.id
order by fields.lft
 * 
 *  */