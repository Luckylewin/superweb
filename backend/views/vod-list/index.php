<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Vod;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '点播分类列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .grid-view td {
        text-align: center;
        vertical-align: middle !important;
    }
</style>
<div class="vod-list-index">

    <p>
        <?= Html::a('创建分类', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'list_sort',
            //'list_pid',
            //'list_sid',
            [
                    'attribute' => 'list_icon',
                    'format' => ['image',['width'=>40]],
                    'options' => ['style' => 'width:100px;'],
                    'value' => 'list_icon'
            ],
            'list_name',
            'list_dir',
            //'list_status',
            //'list_keywords',
            //'list_title',
            //'list_description',
            [
                    'attribute' => 'list_ispay',
                    'value' => function($model) {
                        return Vod::$chargeStatus[$model->list_ispay];
                    }
            ],
            [
                    'attribute' => 'list_price',
                    'value' => function($model) {
                        return $model->list_price . ' 金币';
                    }
            ],
            [
                'attribute' => 'list_trysee',
                'value' => function($model) {
                    return $model->list_trysee . ' 分钟';
                },
                'options' => [
                        'style' => 'width:140px;'
                ]
            ],

            //'list_extend:ntext',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{vods} {type} {update} {delete}',
                    'size' => 'btn-sm',
                    'buttons' => [
                            'vods' => function($url, $model, $key) {
                                return Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;', ['vod/index', 'VodSearch[vod_cid]'=>$model->list_id], [
                                   'class' => 'btn btn-success btn-sm'
                                ]);
                            },
                            'type' => function($url, $model, $key) {
                                return Html::a('查看子类别', ['iptv-type/index', 'list_id'=>$model->list_id], [
                                    'class' => 'btn btn-default btn-sm'
                                ]);
                            }
                    ],
                    'options' => ['style' => 'width:250px;']
            ],
        ],
    ]); ?>
</div>
