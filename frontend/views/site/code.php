<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 26.11.2014
 * Time: 19:08
 */
echo file_get_contents('https://oauth.vk.com/access_token?client_id=4651851&client_secret=tTo5Y2mjIu1y3Qu98kWX&code='.\yii::$app->request->get('code').'&redirect_uri=http://cpa/site/success');
?>

