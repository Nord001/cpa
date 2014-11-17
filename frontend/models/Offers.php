<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

const SYSTEM_ACTIONADS = 1;

/**
 * This is the model class for table "offers".
 *
 * @property integer $id
 * @property string  $name
 * @property integer $system
 * @property integer $offer_id
 * @property string  $date_created
 */
class Offers extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'offers';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['name', 'system', 'offer_id'], 'required'],
			[['system', 'offer_id'], 'integer'],
			[['date_created'], 'safe'],
			[['name'], 'string', 'max' => 255]
		];
	}

	public function beforeSave ($insert) {
		if($this->getIsNewRecord()) {
			$this->date_created = date('Y-m-d H:i:s');
		}
		return parent::beforeSave($insert); // TODO: Change the autogenerated stub
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'           => 'ID',
			'name'         => 'Name',
			'system'       => 'System',
			'offer_id'     => 'Offer ID',
			'date_created' => 'Date Created',
		];
	}
}
