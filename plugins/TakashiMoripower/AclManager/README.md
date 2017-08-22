# TakashiMoripower/AclManager plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require TakashiMoripower/AclManager
```


JcPires\AclManager\Controller\Component\AclManagerComponent

    private function __checkNodeOrSave($path, $alias, $parentId = null){
		```
//      if ($node === false) {
        if (empty($node)) {
		```
//            debug($entity);

		```
	}



AROSのテーブルノードに手動でGroupsのノードを追加する必要がある？