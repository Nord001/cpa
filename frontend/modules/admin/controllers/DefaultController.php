<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller {

	public function behaviors () {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => [
					'index',
				],
				'rules' => [
					[
						'actions' => ['index'],
						'allow'   => true,
						'roles'   => ['admin'],
					],
				],
			],
		];
	}

	public function actionIndex () {
		return $this->render('index');
	}
}
