<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OffersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Offers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offers-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= GridView::widget(
		[
			'dataProvider' => $dataProvider,
			'filterModel'  => $searchModel,
			'columns'      => [
				['class' => 'yii\grid\SerialColumn'],
				'id',
				'name',
				'system',
				'offer_id',
				'date_created',
				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '{view}',
				],
			],
		]
	); ?>

</div>
