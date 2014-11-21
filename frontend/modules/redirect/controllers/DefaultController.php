<?php

namespace app\modules\redirect\controllers;

use app\modules\redirect\components\Errors;
use app\modules\redirect\components\RedirectProcess;
use yii\web\Controller;

class DefaultController extends Controller {

	public function actionIndex () {
		try {
			$link = (new RedirectProcess)
				->setParams()
				->setLog()
				->getURL();
			$this->redirect($link);
			\yii::$app->end();

		} catch (\Exception $e){
			\yii::$app->response->redirect('/reject/'.$e->getMessage());
			\yii::$app->end();
		}

	}

	public function actionReject ($code = false) {
		if(!$code) {
			\yii::$app->end();
		}
		return $this->renderPartial('reject',[
			'message'=> Errors::getText($code),
		]);
	}
}
