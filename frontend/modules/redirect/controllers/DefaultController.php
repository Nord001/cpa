<?php

namespace app\modules\redirect\controllers;

use app\models\Offers;
use app\models\Redirects;
use yii\web\Controller;

class DefaultController extends Controller {

	public function actionIndex () {
//		try {
//			Redirect::i()->setParams()->getURL()->setLog()->run();
//
//		} catch (Exception $e){
//			/**
//			 * TODO: redirect reject
//			 */
//		}

		$params = \Yii::$app->request->getQueryParams();
		$offerID = $params['id'];
		$offer = Offers::findOne($offerID);
		$link = 'http://tracking.actionads.ru/aff_c?offer_id='.$offer->offer_id.'&aff_id='.\Yii::$app->params['affID'];
		$redirects = new Redirects();
		$redirects->system = $offer->system;
		$redirects->offer_id = $offerID;
		$redirects->save();
		$this->redirect($link);
	}
}
