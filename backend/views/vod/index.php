<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Vod;
use yii\helpers\ArrayHelper;
use \common\models\VodList;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '点播列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('发布片源', \yii\helpers\Url::to(['create','vod_cid' => Yii::$app->request->get('VodSearch')['vod_cid']]), ['class' => 'btn btn-success']) ?>

        <?php if(strpos(Yii::$app->request->referrer, 'vod-list') !== false): ?>
            <?= Html::a('返回', null, [
                    'class' => 'btn btn-default',
                    'onclick' => 'history.go(-1)'
            ]) ?>
        <?php else: ?>
            <?= Html::a('返回', ['vod-list/index'], [
                'class' => 'btn btn-default',
                'onclick' => 'history.go(-1)'
            ]) ?>
        <?php endif; ?>

    </p>


    <?php // $this->render('_search', ['model' => $searchModel]); ?>

    <?php $search = Yii::$app->request->get('VodSearch'); ?>


    <?php \yii\widgets\Pjax::begin() ;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'pager' => ['class' => 'common\widgets\goPager', 'go' => true],
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn'
            ],

            [
                "class" => "yii\grid\CheckboxColumn",
                "name" => "id",
            ],

            [
                  'attribute' => 'vod_name',
                  'filterInputOptions' => [
                    'placeholder' => '输入影片名称',
                    'class' => 'form-control'
                  ],
            ],
            //'vod_id',

            [
                    'attribute' => 'vod_type',
                    'filter' => \backend\models\IptvType::getVodType($search['vod_cid']),
                    'filterInputOptions' => [
                            'prompt' => '请选择',
                            'class' => 'form-control'
                    ],
                    'options' => ['style' => 'width:110px;']
            ],

            [
                'attribute' => 'vod_language',
                'filter' => \backend\models\IptvType::getTypeItem($search['vod_cid'], 'vod_language'),
                'filterInputOptions' => [
                    'prompt' => '请选择',
                    'class' => 'form-control'
                ],
                'options' => ['style' => 'width:110px;']
            ],

            /* [
                    'attribute' => 'vod_cid',
                    'filter' => ArrayHelper::map(VodList::getAllList(),  'list_id', 'list_name'),
                    'value' => 'list.list_name'
            ],*/


          /*  [
                    'attribute' => 'vod_ispay',
                    'filter' => Vod::$chargeStatus,
                    'value' => function($model) {
                        return Vod::$chargeStatus[$model->vod_ispay];
                    }
            ],
            [
                    'attribute' => 'vod_price',
                    'options' => ['style' => 'width:60px;']
            ],*/
             [
                  'attribute' => 'vod_gold',
                  'options' => ['style' => 'width:60px;']
              ],
            /*[
                'attribute' => 'vod_hits',
                'options' => ['style' => 'width:100px;']
            ],*/
            /*[
                'attribute' => 'vod_gold',
                'options' => ['style' => 'width:100px;']
            ],*/

            /*[
                    'attribute' => 'vod_stars',
                    'filter' => Vod::$starStatus,
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->star;
                    }
            ],*/
            [
                    'attribute' => 'vod_addtime',
                    'format' => 'date',
                    'value' => function($model) {
                        return $model->vod_addtime;
                    },
                    'options' => ['style' => 'width:100px;']
            ],
            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{push-home}&nbsp;{banner-create}',
                    'buttons' => [
                        'banner-create' => function($url, $model, $key) {
                            return Html::a('banner', ['banner/create','vod_id' => $model->vod_id], [
                                'class' => 'btn btn-default btn-sm'
                            ]);
                        },
                        'push-home' => function($url, $model, $key) {
                            $text = $model->vod_home ? '取消推荐' : '推荐';
                            return Html::a($text, ['vod/push-home','id' => $model->vod_id,'action' => $model->vod_home ? '0' : '1' ], [
                                'class' => 'btn btn-sm ' . ($model->vod_home? 'btn-success' : 'btn-default')
                            ]);
                        },
                    ],
                    'options' => ['style' => 'width:140px;'],
                    'header' => '推送'
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{link-index} {view} {update} {delete}',
                    'buttons' => [

                            'link-index' => function($url, $model) {
                                return Html::a('<i class="glyphicon glyphicon-link"></i> 链接 ', ['link/index', 'vod_id' => $model->vod_id], [
                                    'class' => 'btn btn-success btn-sm'
                                ]);
                            }

                    ],
                    'options' => ['style' => 'width:280px;'],
                    'header' => '操作'
            ],
            //'vod_title',
            //'vod_ename',
            //'vod_keywords',
            //'vod_type',
            //'vod_actor',
            //'vod_director',
            //'vod_content:ntext',
            //'vod_pic',
            //'vod_pic_bg',
            //'vod_pic_slide',
            //'vod_area',
            //'vod_language',
            //'vod_year',
            //'vod_continu',
            //'vod_total',
            //'vod_isend',
            //'vod_addtime:datetime',
            //'vod_filmtime:datetime',
            //'vod_hits',
            //'vod_hits_day',
            //'vod_hits_week',
            //'vod_hits_month',
            //'vod_stars',
            //'vod_status',
            //'vod_up',
            //'vod_down',

            //'vod_price',
            //'vod_trysee',
            //'vod_play',
            //'vod_server',
            //'vod_url:ntext',
            //'vod_inputer',
            //'vod_reurl',
            //'vod_jumpurl',
            //'vod_letter',
            //'vod_skin',
            //'vod_gold',
            //'vod_golder',
            //'vod_length',
            //'vod_weekday',
            //'vod_series',
            //'vod_copyright',
            //'vod_state',
            //'vod_version',
            //'vod_douban_id',
            //'vod_douban_score',
            //'vod_scenario:ntext',


        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end() ;?>

</div>



<?php

echo Html::button("批量删除",[
    'class' => 'gridview btn btn-danger',
]);

$batchDelete = \yii\helpers\Url::to(['vod/batch-delete']);

$requestJs=<<<JS
    $(document).on("click", ".gridview", function () {
                var keys = $("#grid").yiiGridView("getSelectedRows");
                var url = '{$batchDelete}' + '&id=' + keys.join(',');
                window.location.href = url;
            });
JS;

$this->registerJs($requestJs);
?>
