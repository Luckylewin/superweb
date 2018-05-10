<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '链接列表';
$this->params['breadcrumbs'][] = ['label' => '视频列表', 'url' => ['vod/index']];
$this->params['breadcrumbs'][] = $vod->vod_name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vodlink-index">

    <h1><?= Html::encode($vod->vod_name) ?></h1>


    <p>
        <?= Html::a('创建链接', Url::to(['link/create', 'vod_id'=>$vod->vod_id]), ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'video_id',
            'url:url',
            'hd_url:url',
            'season',
            'episode',
            [
                    'class' => 'common\grid\MyActionColumn',
                    'buttons' => [
                            'view' => function($url, $model) {
                                 return Html::a('查看',Url::to(['link/view','vod_id'=>$model->video_id, 'id' => $model->id]),[
                                         'class' => 'btn btn-success btn-xs'
                                 ]);
                            }
                    ]
            ],
        ],
    ]); ?>

</div>
