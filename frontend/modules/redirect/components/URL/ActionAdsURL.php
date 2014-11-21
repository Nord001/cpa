<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 18.11.2014
 * Time: 20:16
 */


namespace app\modules\redirect\components\URL;

class ActionAdsURL {
	private $_params = [
		'offerid' => false,
		'clickid' => false,
	];
	public function setParams ($params) {
		foreach($this->_params as $key=>$value) {
			if(isset($params[$key])) {
				$this->_params[$key] = $params[$key];
			}
		}
	}
	public function getURL () {
		return 'http://tracking.actionads.ru/aff_c?offer_id='.$this->_params['offerid'].'&aff_id='.\Yii::$app->params['affID'].'&aff_sub='.$this->_params['clickid'];
	}
} 
