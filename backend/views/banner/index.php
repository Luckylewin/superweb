<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    td img{
        width: 300px;
    }
</style>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'vod_id',
            'sort',
            'title',
            //'description:ntext',
            [
                    'attribute' => 'pic',
                    'format' => 'image',
            ],
            //'pic_bg',

            ['class' => 'common\grid\MyActionColumn'],
        ],
    ]); ?>
</div>
