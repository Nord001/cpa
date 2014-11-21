<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 18.11.2014
 * Time: 20:11
 */


namespace app\modules\redirect\components;

use app\modules\redirect\components\URL\ActionAdsURL;

class RedirectURL {
	/**
	 * @var ActionAdsURL $_linkConstructor
	 */
	private $_linkConstructor;
	public function setSystem ($num, $params) {
		switch($num) {
			case 1:
				$this->_linkConstructor = new ActionAdsURL();
				break;
		}
		$this->_linkConstructor->setParams($params);
		return $this;
	}

	public function getURL (){
		return $this->_linkConstructor->getURL();
	}

} 
