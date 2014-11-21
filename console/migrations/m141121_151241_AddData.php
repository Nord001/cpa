<?php
use yii\db\Schema;
use yii\db\Migration;

class m141121_151241_AddData extends Migration {

	public function safeUp () {
		$this->insert(
			'{{%offers}}',
			[
				'id'           => 1,
				'name'         => 'Esteticlub - Бесплатная омолаживающая процедура',
				'system'       => 1,
				'offer_id'     => 896,
				'date_created' => '2014-11-18 19:31:41+00'
			]
		);
		$this->insert(
			'{{%offers}}',
			[
				'id'           => 2,
				'name'         => 'Drakensang - браузерная онлайн-игра в стиле фэнтези',
				'system'       => 1,
				'offer_id'     => 450,
				'date_created' => '2014-11-21 17:45:51+00'
			]
		);
	}

	public function safeDown () {
		$this->delete(
			'{{%offers}}',
			[
				'id'           => 1,
				'name'         => 'Esteticlub - Бесплатная омолаживающая процедура',
				'system'       => 1,
				'offer_id'     => 896,
				'date_created' => '2014-11-18 19:31:41+00'
			]
		);
		$this->delete(
			'{{%offers}}',
			[
				'id'           => 2,
				'name'         => 'Drakensang - браузерная онлайн-игра в стиле фэнтези',
				'system'       => 1,
				'offer_id'     => 450,
				'date_created' => '2014-11-21 17:45:51+00'
			]
		);
	}
}
