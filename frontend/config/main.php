<?php
$params = array_merge(
	require(__DIR__.'/../../common/config/params.php'),
	require(__DIR__.'/../../common/config/params-local.php'),
	require(__DIR__.'/params.php'),
	require(__DIR__.'/params-local.php')
);
return [
	'id'                  => 'app-frontend',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'frontend\controllers',
	'components'          => [
		'user'         => [
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
		],
		'authManager'  => [
			'class' => 'yii\rbac\PhpManager',
		],
		'log'          => [
			'traceLevel' => YII_DEBUG
				?3
				:0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => [
						'error',
						'warning'
					],
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [
				'redirect/<offerid:\d+>/<siteid:\d+>/<pubid:\d+>' => 'redirect/default/index',
				'reject/<code:\w+>'                               => 'redirect/default/reject'
			]
		],
	],
	'modules'             => [
		'admin'     => [
			'class' => 'app\modules\admin\Module',
		],
		'publisher' => [
			'class' => 'app\modules\publisher\Module',
		],
		'redirect'  => [
			'class' => 'app\modules\redirect\Module',
		],
	],
	'params'              => $params,
];
