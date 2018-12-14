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
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::img(\common\components\Func::getAccessUrl($model->list_icon), [
                                'width' => '50px',
                                'style' => "border-radius:50px;"
                        ]);
                    },
                    'options' => ['style' => 'width:100px;'],

            ],
            [
                    'attribute' => 'list_name',

            ],
            [
                    'attribute' => 'list_dir',
                    'options' => ['style' => 'width:100px;'],
            ],

            [
                    'attribute' => 'list_ispay',
                    'options' => ['style' => 'width:100px;'],
                    'value' => function($model) {
                        return \backend\blocks\VodBlock::$chargeStatus[$model->list_ispay];
                    }
            ],

            [
                'label' => '影片数量',
                'value' => function($model) {
                    return Vod::find()->where(['vod_cid' => $model->list_id])->count();
                },
                'options' => ['style' => 'width:140px;']
            ],

            [
                'attribute' =>  'list_sort',
                'options' => ['style' => 'width:60px;']
            ],

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
                                return \common\widgets\frameButton::widget([
                                    'content' => '查看子类别',
                                    'url' => \yii\helpers\Url::to(['iptv-type/index', 'list_id'=>$model->list_id]),

                                ]);
                            },
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

