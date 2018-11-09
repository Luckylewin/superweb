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

    <h4><?= Html::encode($this->title) ?></h4>

    <ul id="myTab" class="nav nav-tabs">
        <li class="active">
            <a href="#home" data-toggle="tab">
                基本数据
            </a>
        </li>
        <li><a href="#ios" data-toggle="tab">扩展数据一</a></li>
        <li><a href="#jmeter" data-toggle="tab">扩展数据二</a></li>

    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">

      <?= DetailView::widget([
        'model' => $model,
        'template' => "<tr><th style='width: 200px;'>{label}</th><td>{value}</td></tr>",
        'options' => ['class' => 'table table-striped table-bordered detail-view','style' => 'margin-top:20px;'],

        'attributes' => [

            //'list.list_name',
            'vod_type',
            'vod_area',
            'vod_year',
            'vod_length',
            [
                'attribute' => 'vod_pic',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->vod_pic) {
                        return '<div style="width: 200px;min-height: 107px;float: left"><img width="170" src="'. $model->vod_pic.'"></div>'
                                . '<div >' . $model->vod_content . '</div>'
                            ;
                    }
                    return '';
                }
            ],

        ],
    ])?>

        </div>
        <div class="tab-pane fade" id="ios">

            <?= DetailView::widget([
                'model' => $model,
                'template' => "<tr><th style='width: 200px;'>{label}</th><td>{value}</td></tr>",
                'options' => ['class' => 'table  table-striped table-bordered detail-view','style' => 'margin-top:20px;'],
                'attributes' => [

                    'vod_origin_url',
                    'vod_actor',
                    'vod_total',
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
            ])?>

        </div>
        <div class="tab-pane fade" id="jmeter">
            <?= DetailView::widget([
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
            ])?>
        </div>

    </div>




    <?php
    /*DetailView::widget([
        'model' => $model,
        'template' => "<tr><th style='width: 200px;'>{label}</th><td>{value}</td></tr>",
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
        'attributes' => [
            'vod_id',
            'list.list_name',
            'vod_name',
            'vod_ename',
            'vod_url:ntext',
            'vod_title',
            'vod_keywords',
            'vod_type',
            'vod_actor',
            'vod_director',
            'vod_content:raw',
            [
                'attribute' => 'vod_pic',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->vod_pic) {
                        return '<img width="100" src="'. $model->vod_pic.'">';
                    }
                    return '';
                }
            ],
            'vod_pic_bg',
            'vod_pic_slide',
            'vod_area',
            'vod_language',
            'vod_year',
            'vod_continu',
            'vod_total',
            'vod_isend',
            'vod_addtime:datetime',
            'vod_filmtime:datetime',
            'vod_hits',
            'vod_hits_day',
            'vod_hits_week',
            'vod_hits_month',
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
            'vod_multiple',
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
    ])*/

    ?>


</div>
