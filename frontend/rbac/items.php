<?php
/**
 * http://habrahabr.ru/post/235485/
 */
use yii\rbac\Item;

return [
    'publisher' => [
        'type' => Item::TYPE_ROLE,
    ],
	'admin' => [
		'type' => Item::TYPE_ROLE,
	]
];
