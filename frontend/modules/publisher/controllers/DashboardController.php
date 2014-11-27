<?php

namespace app\modules\publisher\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class DashboardController extends Controller {

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
						'roles'   => ['publisher'],
					],
				],
			],
		];
	}

	public function actionIndex () {
		return $this->render('index');
	}
}
