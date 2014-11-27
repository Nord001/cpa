<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class VkController extends Controller {

	const CITY = 'Брянск';
	const SEX = 1;  //пол, 1 — женщина, 2 — мужчина, 0 (по умолчанию) — любой.
	const AGE_TO = 23;
	const AGE_FROM = 18;
	const GROUP_ID = 76257495;
	const GROUP_NAME = 'Сарафанное радио';
	const GROUP_URL = 'https://vk.com/safanbryansk';
	const TIMEOUT = 180;
	private $_token;
	private $_results;
	const SORT_TYPE = 1; // 0 - по популярности, 1 - по дате регистрации
	const OFFSET = 0; // 0 - по популярности, 1 - по дате регистрации
	const COUNT = 1000; // 0 - по популярности, 1 - по дате регистрации

	public function actionVk () {
		return $this->render('vk');
	}

	/**
	 * TODO: https://vk.com/dev/methods
	 */
	public function actionCode () {
		$this->setHeader();
		$this->refreshPage();
		$this->echoInfo();
		$this->checkAccess();
		switch(mt_rand(1,3)) {
			case 1:
				$this->send40Messages();
				break;
			case 2:
				$this->send40InviteToFriend();
				break;
		}
	}

	public function getFriends () {
		$url              = $this->getBaseURL('friends.get');
		$this->_results[] = $this->runCommand($url);
		return $this;
	}

	public function getUsers ($from = self::AGE_FROM, $to = self::AGE_TO, $sex = self::SEX, $search = '') {
		$url              = $this->getBaseURL(
				'users.search'
			).'&q='.$search.'&sord='.self::SORT_TYPE.'&offset='.self::OFFSET.'&count='.self::COUNT.'&hometown='.self::CITY.'&sex='.$sex.'&age_from='.$from.'&age_to='.$to;
		$this->_results[] = $this->runCommand($url);
		return $this;
	}

	public function getUser ($ids) {
		if (is_array($ids)) {
			$ids = implode(',', $ids);
		}
		$url              = $this->getBaseURL(
				'users.get'
			).'&user_ids='.$ids.'&fields=sex,bdate,city,country,photo_50,photo_100,photo_200_orig,photo_200,photo_400_orig,photo_max,photo_max_orig,photo_id,online,online_mobile,domain,has_mobile,contacts,connections,site,education,universities,schools,can_post,can_see_all_posts,can_see_audio,can_write_private_message,status,last_seen,common_count,relation,relatives,counters,screen_name,maiden_name,timezone,occupation,activities,interests,music,movies,tv,books,games,about,quotes';
		$this->_results[] = $this->runCommand($url);
		return $this;
	}

	public function getMessages () {
		$url              = $this->getBaseURL('messages.get');
		$this->_results[] = $this->runCommand($url);
		return $this;
	}

	public function sendMessage ($user_id, $message) {
		$url              = $this->getBaseURL('messages.send')."&user_id=$user_id&message=".urlencode($message);
		$this->_results[] = $this->runCommand($url);
		return $this;
	}

	public function getLastResult () {
		return $this->_results[sizeof($this->_results) - 1]['response'];
	}

	public function echoLastResult () {
		echo "<pre>";
		print_r($this->getLastResult());
		echo "</pre>";
		return $this;
	}

	public function setHeader () {
		header("Content-Type: text/html; charset=utf-8");
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

	public function getToken () {
		if (!$this->_token) {
			$filePath = $this->getBasePath().'access.txt';
			if (file_exists($filePath)) {
				$this->_token = file_get_contents($filePath);
				$this->_token = Json::decode($this->_token);
				$this->_token = $this->_token[0]['access_token'];
			} else {
				throw new \Exception('Не найден файл с токеном.');
			}
		}
		return $this->_token;
	}

	private function runCommand ($url) {
		echo '('.date('d.m.Y H:i:s').") Команда ".$url.'<br />';
		$result = Json::decode($this->get_web_page($url));
		echo "<pre>Результат: \r\n";
		print_r($result);
		echo "</pre>";
		return $result;
	}

	private function getBaseURL ($command) {
		return "https://api.vk.com/method/".$command."?&access_token=".$this->getToken();
	}

	public function getBasePath () {
		return \yii::$app->basePath.DIRECTORY_SEPARATOR;
	}

	public function saveToFile ($filename, $data) {
		$filename = $this->getBasePath().$filename;
		file_put_contents($filename, Json::encode($data));
	}

	public function readFromFile ($filename) {
		$filename = $this->getBasePath().$filename;
		if (!file_exists($filename)) {
			throw new \Exception('Файл для чтения '.$filename.' не найден.');
		}
		return Json::decode(file_get_contents($filename));
	}

	private function inviteToFriends ($user_id, $text) {
		$url              = $this->getBaseURL('friends.add').'&user_id='.$user_id.'&text='.$text;
		$this->_results[] = $this->runCommand($url);
		return $this;
	}

	private function inviteToGroup ($user_id) {
		$url              = $this->getBaseURL('groups.invite').'&user_id='.$user_id.'&group_id='.self::GROUP_ID;
		$this->_results[] = $this->runCommand($url);
		return $this;
	}

	private function addUsersInList () {
		$list = $this->getPeople();
		if (!$list) {
			$list = [];
		}
		for($i = self::AGE_FROM;$i <= self::AGE_TO;$i++) {
			$lastResult  = $this->getUsers($i, $i)->getLastResult();
			$array_slice = array_slice($lastResult, 1);
			foreach($array_slice as $user) {
				$list[$user['uid']] = $user;
			}
			sleep(1);
		}
		echo "Всего ".sizeof($list).' записей';
		$this->savePeople($list);
	}

	private function savePeople ($peopleList) {
		$this->saveToFile('people.txt', $peopleList);
	}

	private function getPeople () {
		return $this->readFromFile('people.txt');
	}

	private function send40InviteToFriend () {
		$list = $this->getPeople();
		$list = array_slice($list, 0, 40);
		foreach($list as $key => $user) {
			$result = $this->inviteToFriends($user['uid'],'')->getLastResult();
			if(!$result['error']) {
				$this->moveToProcessList($user['uid'], $user, 'invite_in_friend');
			}
			die();
		}
	}

	private function send40Messages () {
		$list = $this->getPeople();
		$list = array_slice($list, 0, 40);
		foreach($list as $key => $user) {
			$message = $this->generateMessage($user['uid']);
			$result = $this->sendMessage($user['uid'], $message)->getLastResult();

			if(is_numeric($result)) {
				$this->moveToProcessList($user['uid'], $user, 'send_message', $message);
			}
			die();
		}
	}

	private function send40InviteToGroup () {
		$list = $this->getPeople();
		$list = array_slice($list, 0, 40);
		foreach($list as $key => $user) {
			$result = $this->inviteToGroup($user['uid'])->getLastResult();
			if(!$result['error']) {
				$this->moveToProcessList($user['uid'], $user, 'invite_in_group');
			}
		}
	}

	public function moveToProcessList ($id, $array, $action, $message='') {
		$all = $this->getPeople();
		unset($all[$id]);
		$this->savePeople($all);
		$arr      = $this->getProcessList();
		$arr[$id] = $array;
		$arr[$id]['action'] = $action;
		$arr[$id]['message'] = $message;
		$arr[$id]['date'] = date('Y-m-d');
		$arr[$id]['fulldate'] = date('Y-m-d H:i:s');
		$this->saveProcessList($arr);
	}

	private function getProcessList () {
		return $this->readFromFile('processList.txt');
	}

	private function saveProcessList ($processList) {
		$this->saveToFile('processList.txt', $processList);
	}

	private function generateMessage ($id) {
		$list = [
			[
				'Привет, %NAME%. ',
				'Доброго времени суток, %NAME%. ',
				'Хорошего дня, %NAME%. ',
				'Здравствуйте, %NAME%. ',
			],
			[
				'Хочу рассказать ',
				'Хочу поделиться информацией ',
				'Хочу проинформировать ',
			],
			[
				'про группу "%GROUP_NAME%". ',
				'о группе "%GROUP_NAME%". ',
			],
			[
				'В группе всегда свежие новости о том, какие события планируются в Брянске. ',
				'Группа публикует самые свежие посты о том, какие события планируются в Брянске. ',
			],
			[
				'Предлагаю вступить в группу %GROUP_URL%. ',
				'Адрес группы %GROUP_URL%, вступи, чтобы быть в курсе новых событий. ',
				'Группа находится по адресу %GROUP_URL%. Вступай, чтобы быть в курсе новых событий. ',
			],
			[
				'Прошу прощения за Ваше потраченное время.',
				'Хочу попросить прощения за потраченное Вами время.',
			]
		];
		$message = '';
		foreach($list as $item) {
			$message.= $item[mt_rand(0, sizeof($item)-1)];
		}
		$user = $this->getUser($id)->getLastResult();
		$username = $user['0']['first_name'];
		$message = str_replace('%NAME%', $username, $message);
		$message = str_replace('%GROUP_NAME%', self::GROUP_NAME, $message);
		$message = str_replace('%GROUP_URL%', self::GROUP_URL, $message);
		return $message;
	}

	private function echoInfo () {
		$list = $this->getProcessList();
		$result = [];
		foreach($list as $item) {
			if(!isset($result[$item['date']][$item['action']])) {
				$result[$item['date']][$item['action']] = 0;
			}
			$result[$item['date']][$item['action']]++;
		}
		foreach($result as $date => $items) {
			echo "<h4>$date</h4>";
			foreach($items as $name=>$count) {
				echo $name.': '.$count.'<br />';
			}
		}

	}

	private function checkAccess () {
		$list = $this->getProcessList();
		$date = array_pop($list)['fulldate'];
		$date = strtotime($date);
		echo "Крайнее обновление было ".date('H:i:s', $date).'<br />';
		if(time()-$date<=self::TIMEOUT) {
			echo "Пока еще не настал срок!";
			die();
		}
	}

	private function refreshPage () {
		echo '<html><head><meta http-equiv="refresh" content="'.self::TIMEOUT.'"></head><body>';
	}
}
