<?php
namespace frontend\controllers;

use app\models\forms\SignupForm;
use Yii;
use common\models\forms\LoginForm;
use frontend\models\forms\PasswordResetRequestForm;
use frontend\models\forms\ResetPasswordForm;
use frontend\models\forms\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function behaviors () {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => [
					'logout',
					'signup',
					'request-password-reset',
					'reset-password',
				],
				'rules' => [
					[
						'actions' => [
							'signup',
							'request-password-reset',
							'reset-password'
						],
						'allow'   => true,
						'roles'   => ['?'],
					],
					[
						'actions' => ['logout'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions () {
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST
					?'testme'
					:null,
			],
		];
	}

	public function actionIndex () {
		return $this->render('index');
	}

	public function actionLogin () {
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render(
				'login',
				[
					'model' => $model,
				]
			);
		}
	}

	public function actionLogout () {
		Yii::$app->user->logout();
		return $this->goHome();
	}

	public function actionContact () {
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash(
					'success',
					'Thank you for contacting us. We will respond to you as soon as possible.'
				);
			} else {
				Yii::$app->session->setFlash('error', 'There was an error sending email.');
			}
			return $this->refresh();
		} else {
			return $this->render(
				'contact',
				[
					'model' => $model,
				]
			);
		}
	}

	public function actionAbout () {
		return $this->render('about');
	}

	public function actionSignup () {
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				if (Yii::$app->getUser()->login($user)) {
					return $this->goHome();
				}
			}
		}
		return $this->render(
			'signup',
			[
				'model' => $model,
			]
		);
	}

	public function actionRequestPasswordReset () {
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->getSession()->setFlash('success', 'Проверьте ваш e-mail для дальнейших инструкций.');
				return $this->goHome();
			} else {
				Yii::$app->getSession()->setFlash(
					'error',
					'Извините, мы не можем сбросить пароль по указанному e-mail.'
				);
			}
		}
		return $this->render(
			'requestPasswordReset',
			[
				'model' => $model,
			]
		);
	}

	public function actionResetPassword ($token) {
		try {
			$model = new ResetPasswordForm($token);
		} catch(InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->getSession()->setFlash('success', 'Новый пароль был сохранен.');
			return $this->goHome();
		}
		return $this->render(
			'resetPassword',
			[
				'model' => $model,
			]
		);
	}

	public function actionVk () {
		return $this->render('vk');
	}

	/**
	 * TODO: https://vk.com/dev/methods
	 */
	public function actionCode () {
		$filePath = \yii::$app->basePath.DIRECTORY_SEPARATOR.'access.txt';
		if(file_exists($filePath)){
			$arr = file_get_contents($filePath);
			$arr = Json::decode($arr);
		} else {
			$arr = [];
			$result = $this->get_web_page('https://oauth.vk.com/access_token?client_id=4651851&client_secret=tTo5Y2mjIu1y3Qu98kWX&code='.\yii::$app->request->get('code').'&redirect_uri=http://cpa/site/code');
			$result = Json::decode($result);
			$arr[] = $result;
			file_put_contents($filePath, Json::encode($arr));
		}
		header("Content-Type: text/html; charset=utf-8");
		$token = $arr[0]['access_token'];
		$result = $this->getMessages($arr[0]['access_token']);
		$result = Json::decode($result, true);
		$result = $this->sendMessage($arr[0]['access_token'], 'Тест', 38064385);
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		die();

	}
	public function getFriends ($token) {
		return $this->get_web_page("https://api.vk.com/method/fiends.get?&access_token=".$token);
	}
	public function getMessages ($token) {
		return $this->get_web_page("https://api.vk.com/method/messages.get?&access_token=".$token);
	}

	public function get_web_page ($url) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION , 1);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	private function sendMessage ($access_token, $message, $user_id) {
		$url = "https://api.vk.com/method/messages.send?user_id=$user_id&message=$message&access_token=".$access_token;
		return $this->get_web_page($url);
	}
}
