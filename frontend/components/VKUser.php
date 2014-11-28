<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 28.11.2014
 * Time: 15:42
 */
namespace app\components;

use yii\base\Exception;

class VKUser {

	const PHONE = '79627120849';
	const CODE = '77016';
	private $commandComponent;
	private $_first_names = [
		'Мария',
		'Жанна',
		'Олеся',
		'Ольга',
		'Вера',
		'Маргарита',
	];
	private $_last_names = [
		'Снежко',
		'Мирильченко',
		'Копнова',
		'Решетникова',
		'Сазонова',
		'Марильченкова',
	];

	public function __construct (VKCommands $commandComponent) {
		$this->commandComponent = $commandComponent;
	}

	public function registrationUser () {
		if (!$this->checkPhone()) {
			throw new Exception('Номер '.self::PHONE.' уже занят.');
		}
		if (!$signup = $this->signUp()) {
			throw new Exception('не удалось зарегистрировать пользователя.');
		}
		return $signup;
	}

	public function confirmUser () {
		$confirmParams = [
			'phone' => self::PHONE,
			'code'  => self::CODE,
		];
		return $this->commandComponent->authConfirm($confirmParams)->getLastResult();
	}

	public function setProfileInfo () {
		return $this;
	}

	public function fillWall () {
		return $this;
	}

	public function moveToGetToken () {
		return $this;
	}

	public function downloadPhotos () {
		return $this;
	}

	public function setMainPhoto () {
		return $this;
	}

	private function checkPhone () {
		$params['phone'] = self::PHONE;
		return $this->commandComponent->authCheckPhone($params)->getLastResult();
	}

	private function signUp () {
		$signUpParams = [
			'first_name' => $this->_first_names[mt_rand(0, sizeof($this->_first_names) - 1)],
			'last_name'  => $this->_last_names[mt_rand(0, sizeof($this->_last_names) - 1)],
			'phone'      => self::PHONE,
			'password'   => \yii::$app->security->generateRandomString('12'),
			'sex'        => 1,
		];
		return $this->commandComponent->authSignup($signUpParams)->getLastResult();
	}
}
