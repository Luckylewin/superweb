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
    .grid-view th ,.grid-view td {
        text-align: center;
        vertical-align: middle !important;
    }

</style>
<div class="vod-list-index">

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            //'list_pid',
            //'list_sid',
            [
                    'attribute' => 'list_icon',
                    'format' => ['image',['width'=>40]],
                    'options' => ['style' => 'width:100px;'],
                    'value' => 'list_icon'
            ],
            [
                    'attribute' => 'list_name',

            ],
            [
                    'attribute' => 'list_dir',
                    'options' => ['style' => 'width:100px;'],
            ],
            //'list_status',
            //'list_keywords',
            //'list_title',
            //'list_description',
            [
                    'attribute' => 'list_ispay',
                    'options' => ['style' => 'width:100px;'],
                    'value' => function($model) {
                        return \backend\blocks\VodBlock::$chargeStatus[$model->list_ispay];
                    }
            ],
            [
                    'attribute' => 'list_price',
                    'value' => function($model) {
                        return $model->list_price . ' ' . Yii::t('backend', 'gold');
                    }
            ],
            [
                'attribute' => 'list_trysee',
                'value' => function($model) {
                    return $model->list_trysee . ' ' . Yii::t('backend', 'minutes');
                },
                'options' => ['style' => 'width:140px;']
            ],

            [
                'attribute' =>  'list_sort',
                'options' => ['style' => 'width:60px;']
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
                    'options' => ['style' => 'width:280px;']
            ],
        ],
    ]); ?>
</div>


<?php if(strpos(Yii::$app->request->hostInfo, '207.38.90.29') !== false || strpos(Yii::$app->request->hostInfo, 'vitvbox.net') !== false): ?>
<p>
    <?= Html::a('更新点播数据', \yii\helpers\Url::to(['client/anna-iptv']), ['class' => 'btn btn-info']) ?>
</p>
<?php endif; ?>
