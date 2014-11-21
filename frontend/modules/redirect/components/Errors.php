<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 18.11.2014
 * Time: 19:57
 */


namespace app\modules\redirect\components;

class Errors {
	const OFFER_NOT_FOUND = 1;
	private static $_list = [
		self::OFFER_NOT_FOUND => 'Оффер не найден',
	];
	public static function getText ($num) {
		if(isset(self::$_list[$num])) {
			return self::$_list[$num];
		}
		return false;
	}

} 
