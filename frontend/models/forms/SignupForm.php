<?php
namespace app\models\forms;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model {

	public $username;
	public $email;
	public $password;

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Это имя пользователя уже используется.'],
			['username', 'string', 'min' => 3, 'max' => 255],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже используется.'],

			['password', 'required'],
			['password', 'string', 'min' => 6],
		];
	}

	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function signup () {
		if ($this->validate()) {
			$user = new User();
			$user->username = $this->username;
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->generateAuthKey();
			$user->save();


			// the following three lines were added:
			$auth = Yii::$app->authManager;
			$authorRole = $auth->getRole('publisher');
			$auth->assign($authorRole, $user->getId());
			return $user;
		}
		return null;
	}

	public function attributeLabels () {
		return [
			'username' => 'Логин',
			'password' => 'Пароль',
			'email'    => 'E-mail',
		];
	}
}
