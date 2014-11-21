<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 18.11.2014
 * Time: 19:36
 */
namespace app\modules\redirect\components;

use app\models\Offers;
use app\models\Redirects;

class RedirectProcess {

	private $_params = [
		'pubid'   => false,
		'siteid'  => false,
		'offerid' => false,
		'subid1'  => false,
		'subid2'  => false,
		'subid3'  => false,
		'subid4'  => false,
		'subid5'  => false,
		'clickid' => false,
	];
	private $_url;
	private $_offer;

	public function setParams () {
		foreach($this->_params as $key => $value) {
			if(\yii::$app->request->get($key)) {
				$this->_params[$key] = \yii::$app->request->get($key);
			}
		}
		$this->_params['clickid'] = md5(microtime(true));
		return $this;
	}

	public function getURL () {
		$params = $this->_params;
		$params['offerid'] = $this->getOffer()['offer_id'];
		if(!$this->_url){
			$this->_url = (new RedirectURL())
				->setSystem($this->getOffer()['system'], $params)
				->getURL();
		}
		echo "<pre>";
		print_r($this->_url);
		echo "</pre>";
		die();
		return $this->_url;
	}

	public function setLog () {
		$redirect = new Redirects();
		$redirect->system = $this->getOffer()['system'];
		$redirect->offer_id = $this->getOffer()['id'];
		$redirect->click_id = $this->_params['clickid'];
		$redirect->save(false);
		return $this;
	}

	public function run () {
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return Offers
	 */
	public function getOffer () {
		if(!$this->_offer) {
			$this->_offer = Offers::findOne($this->_params['offerid']);
			if(!$this->_offer) {
				throw new \Exception(Errors::OFFER_NOT_FOUND);
			}
		}
		return $this->_offer;
	}
}
