<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Vod */

$this->title = $model->vod_id;
$this->params['breadcrumbs'][] = ['label' => 'Vods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
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
                    return \common\models\Vod::$chargeStatus[$model->vod_ispay];
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
            ]
        ],
    ]) ?>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->vod_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->vod_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?=  Html::a('返回', ['vod/index', 'VodSearch[vod_cid]'=>$model->vod_cid], [
            'class' => 'btn btn-default'
        ]); ?>
    </p>

</div>
