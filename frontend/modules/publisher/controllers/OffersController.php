<?php

namespace app\modules\publisher\controllers;

use Yii;
use app\models\offers;
use app\models\search\OffersSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OffersController implements the CRUD actions for offers model.
 */
class OffersController extends Controller {

	public function behaviors () {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [
							'index',
							'view',
						],
						'allow'   => true,
						'roles'   => ['publisher'],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all offers models.
	 * @return mixed
	 */
	public function actionIndex () {
		$searchModel  = new OffersSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render(
			'index',
			[
				'searchModel'  => $searchModel,
				'dataProvider' => $dataProvider,
			]
		);
	}

	/**
	 * Displays a single offers model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView ($id) {
		return $this->render(
			'view',
			[
				'model' => $this->findModel($id),
			]
		);
	}

	/**
	 * Finds the offers model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return offers the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel ($id) {
		if (($model = offers::findOne($id))!==null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
