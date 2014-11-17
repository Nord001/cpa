<?php
use yii\db\Schema;
use yii\db\Migration;

class m140524_201445_CreateTable_Redirect extends Migration {

	public function up () {
		$this->createTable(
			 '{{%redirects}}',
			 [
				 'id'              => Schema::TYPE_PK,
				 'system'          => Schema::TYPE_INTEGER.' NOT NULL',
				 'offer_id'        => Schema::TYPE_INTEGER.' NOT NULL',
				 'date_redirected' => Schema::TYPE_TIMESTAMP.' WITH TIME ZONE',
				 'ip'              => Schema::TYPE_STRING,
				 'user_agent'      => Schema::TYPE_STRING,
			 ]
		);
	}

	public function down () {
		$this->dropTable('{{%redirects}}');
	}
}
