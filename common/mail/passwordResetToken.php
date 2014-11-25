<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

Здравствуйте, <?= Html::encode($user->username) ?>,

Проследуйте по указанной ниже ссылке, чтобы сбросить пароль:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>
