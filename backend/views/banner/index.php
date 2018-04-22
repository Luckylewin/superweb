<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Banner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                    'options' => [
                            'width' => '100px'
                    ]
            ],
            //'pic_bg',

            ['class' => 'common\grid\MyActionColumn'],
        ],
    ]); ?>
</div>
