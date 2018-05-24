<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Vod;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '点播分类列表';
$this->params['breadcrumbs'][] = $this->title;
?>

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
                    'format' => ['image',['width'=>70]],
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
                }
            ],

            //'list_extend:ntext',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{vods} {view} {update} {delete}',
                    'buttons' => [
                            'vods' => function($url, $model, $key) {

                                return Html::a('查看片源', ['vod/index', 'VodSearch[vod_cid]'=>$model->list_id], [
                                   'class' => 'btn btn-default btn-xs'
                                ]);
                            }
                    ],
                    'options' => ['style' => 'width:250px;']
            ],
        ],
    ]); ?>
</div>
