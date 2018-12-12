<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Vod */

$this->title = $model->vod_name;
$this->params['breadcrumbs'][] = ['label' => 'Vods', 'url' => Yii::$app->request->referrer];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-view">

    <?php if (Yii::$app->request->isAjax == false): ?>
    <h4><?= Html::encode($this->title) ?></h4>
    <?php endif; ?>

    <?= \yii\bootstrap\Tabs::widget([
            'items' => [
                            [
                                'label' => '基本数据',
                                'content' => DetailView::widget([
                                    'model' => $model,
                                    'template' => "<tr><th style='width: 200px;'>{label}</th><td>{value}</td></tr>",
                                    'options' => ['class' => 'table table-striped table-bordered detail-view','style' => 'margin-top:20px;'],

                                    'attributes' => [
                                        //'list.list_name',
                                        'vod_type',
                                        'vod_area',
                                        'vod_year',
                                        'vod_origin_url',
                                        [
                                            'attribute' => 'vod_pic',
                                            'format' => 'raw',
                                            'value' => function($model) {
                                                if ($model->vod_pic) {
                                                    return '<div style="width: 83px;min-height: 43px;float: left"><img width="83" src="'. $model->vod_pic.'"></div>'
                                                        . '<div >' . $model->vod_content . '</div>'
                                                        ;
                                                }
                                                return '';
                                            }
                                        ],

                                    ],
                                ]),
                                'active' => true
                            ],
                            [
                                'label' => '扩展数据一',
                                'content' => DetailView::widget([
                                    'model' => $model,
                                    'template' => "<tr><th style='width: 200px;'>{label}</th><td>{value}</td></tr>",
                                    'options' => ['class' => 'table  table-striped table-bordered detail-view','style' => 'margin-top:20px;'],
                                    'attributes' => [
                                        'vod_origin_url',
                                        'vod_actor',
                                        'vod_total',
                                        'vod_hits',
                                        'vod_addtime:datetime',
                                        'vod_director',

                                        [
                                            'attribute' => 'vod_multiple',
                                            'value' => function($model) {
                                                return $model->vod_multiple ? '是' :'否';
                                            }
                                        ],

                                        'vod_continu',
                                        'vod_ename',
                                        'vod_douban_id',
                                        //'vod_weekday',
                                        'vod_pic_bg',
                                        'vod_pic_slide',
                                        //'vod_reurl',
                                        'vod_keywords',
                                        //'vod_series',
                                        'vod_language',
                                        //'vod_version',
                                        'vod_content'
                                    ],
                                ]),
                                'headerOptions' => [],
                                'visible' => Yii::$app->request->isAjax ? false : true
                            ],
                            [
                                'label' => '扩展数据二',
                                'content' =>  DetailView::widget([
                                'model' => $model,
                                'template' => "<tr><th style='width: 200px;'>{label}</th><td>{value}</td></tr>",
                                'options' => ['class' => 'table table-striped  table-bordered detail-view','style' => 'margin-top:20px;'],
                                'attributes' => [
                                    'vod_id',
                                    [
                                        'attribute' => 'vod_stars',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return $model->getStar();
                                        }
                                    ],
                                    'vod_status',
                                    'vod_up',
                                    'vod_down',
                                    [
                                        'attribute' => 'vod_ispay',
                                        'value' => function($model) {
                                            return $model->getChargeText();
                                        }
                                    ],
                                    'vod_price',
                                    'vod_trysee',
                                    //'vod_play',
                                    //'vod_server',
                                    //'vod_url:ntext',
                                    //'vod_inputer',
                                    //'vod_reurl',
                                    //'vod_jumpurl',
                                    'vod_letter',
                                    //'vod_skin',
                                    'vod_gold',
                                    'vod_golder',
                                    'vod_length',
                                    //'vod_weekday',
                                    //'vod_series',
                                    //'vod_copyright',
                                    'vod_state',
                                    'vod_version',
                                    'vod_douban_id',
                                    'vod_douban_score',
                                    'vod_scenario:ntext',
                                    [
                                        'attribute' => 'vod_home',
                                        'value' => function($model) {
                                            return $model->vod_home ? '是':"否";
                                        }
                                    ],
                                    'vod_imdb_id',
                                    'vod_imdb_score',
                                    'vod_fill_flag'
                                ],
                            ]),
                                'headerOptions' => [],
                                'visible' => Yii::$app->request->isAjax ? false : true
                            ]
                        ]
        ]); ?>

</div>
