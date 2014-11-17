<?php
use yii\db\Schema;
use yii\db\Migration;

class m140524_201442_CreateTable_Offers extends Migration {

	public function up () {
		$this->createTable(
			 '{{%offers}}',
			 [
				 'id'           => Schema::TYPE_PK,
				 'name'         => Schema::TYPE_STRING.' NOT NULL',
				 'system'       => Schema::TYPE_INTEGER.' NOT NULL',
				 'offer_id'     => Schema::TYPE_INTEGER.' NOT NULL',
				 'date_created' => Schema::TYPE_TIMESTAMP.' WITH TIME ZONE',
			 ]
		);
	}

	public function down () {
		$this->dropTable('{{%offers}}');
	}
}
