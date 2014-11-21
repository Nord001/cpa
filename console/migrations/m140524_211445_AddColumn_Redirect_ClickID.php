<?php
use yii\db\Schema;
use yii\db\Migration;

class m140524_211445_AddColumn_Redirect_ClickID extends Migration {

	public function up () {
		$this->addColumn('{{%redirects}}','click_id',Schema::TYPE_STRING);
	}

	public function down () {
		$this->dropColumn('{{%redirects}}','click_id');
	}
}
