<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 28.11.2014
 * Time: 15:42
 */
namespace app\components;

use yii\helpers\Json;

class VKCommands {

	const CITY = 'Брянск';
	const SEX = 1;  //пол, 1 — женщина, 2 — мужчина, 0 (по умолчанию) — любой.
	const AGE_TO = 23;
	const AGE_FROM = 18;
	const GROUP_ID = 76257495;
	const GROUP_NAME = 'Сарафанное радио';
	const GROUP_URL = 'https://vk.com/safanbryansk';
	const TIMEOUT = 90;
	const SORT_TYPE = 1; // 0 - по популярности, 1 - по дате регистрации
	const OFFSET = 0; // 0 - по популярности, 1 - по дате регистрации
	const COUNT = 1000;
	const APP_ID = 4651851;
	const SECRET_CODE = 'tTo5Y2mjIu1y3Qu98kWX';
	const TOKEN = '4b69417bbf945454bfdca4ea5eaec9480857cb21a32140db1d7a59b2cf52a37cde7e4b61a1511973f9c8b';
	const TEST_MODE = 0;
	private $_results;
	private $_token;

	public function authCheckPhone ($params) {
		$params['client_id']     = self::APP_ID;
		$params['client_secret'] = self::SECRET_CODE;
		$url                     = $this->getBaseURL('auth.checkPhone', false).'&'.http_build_query($params);
		$this->_results[]        = $this->runCommand($url);
		return $this;
	}

	public function getLastResult () {
		return $this->_results[sizeof($this->_results) - 1];
	}

	public function echoLastResult () {
		echo "<pre>";
		print_r($this->getLastResult());
		echo "</pre>";
		return $this;
	}

	public function get_web_page ($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	private function getBaseURL ($command, $withToken = true, $token = false) {
		return "https://api.vk.com/method/".$command.($withToken
			?"?&access_token=".($token
				?$token
				:$this->getToken())
			:'?');
	}

	private function runCommand ($url) {
		echo '('.date('d.m.Y H:i:s').") Команда ".$url.'<br />';
		$result = Json::decode($this->get_web_page($url));
		return $result;
	}

	public function getToken () {
		if (!$this->_token) {
			$this->_token = self::TOKEN;
		}
		return $this->_token;
	}

	public function authSignup ($signUpParams) {
		$signUpParams['client_id']     = self::APP_ID;
		$signUpParams['client_secret'] = self::SECRET_CODE;
		$signUpParams['de']     = self::TEST_MODE;
		$url                           = $this->getBaseURL('auth.signup', false).'&'.http_build_query($signUpParams);
		$this->_results[]              = $this->runCommand($url);
		return $this;
	}

	public function authConfirm ($confirmParams) {
		$confirmParams['client_id']     = self::APP_ID;
		$confirmParams['client_secret'] = self::SECRET_CODE;
		$url                            = $this->getBaseURL('auth.checkPhone', false).'&'.http_build_query($confirmParams);
		$this->_results[]               = $this->runCommand($url);
		return $this;
	}
} 
