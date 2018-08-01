<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\KaraokeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Karaoke video');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-name-karaoke-index">

    <?php //$this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered'],
        'pager' => [
            'class' => 'common\widgets\goPager',
            'go' => true,
            'maxButtonCount' => 15
        ],
        'layout' => "\n{items}{summary}\n{pager}\n",
        'columns' => [
            [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => ['style' => 'width:50px;'
                    ]
            ],
            [
                'attribute' => 'albumName',
                'options' => ['style' => 'width:300px;' ]
            ],
            [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'value' => function($model) {
                        if ($model->source == 'Youtube') {
                            return Html::a($model->url, "https://www.youtube.com/watch?v=" . $model->url, [
                                'target' => '_blank'
                            ]);
                        } else {
                            return Html::a($model->url, \common\components\Func::getAccessUrl($model->url), [
                                'target' => '_blank'
                            ]);
                        }
                    }
            ],

            [
                    'attribute' => 'hit_count',
                    'label' => Yii::t('backend', 'heat'),
                    'options' => ['style' => 'width:70px;']
            ],

            [
                    'attribute' => 'area',
                    'filter' => \backend\models\Karaoke::getLang(),
                    'options' => ['style' => 'width:70px;']

            ],

            [
                    'attribute' => 'is_del',
                    'filter' => \backend\models\Karaoke::$delStatus,
                    'options' => ['style' => 'width:100px;'],
                    'value' => function($model) {
                        return $model->status;
                    }
            ],

            [
                    'attribute' => 'utime',
                    'format' => 'date',
                    'options' => [
                        'style' => 'width:100px;'
                    ]
            ],

            [
                'class' => 'common\grid\MyActionColumn',
                'header' => Yii::t('backend', 'Operation')
            ],



            //'directors',
            //'tags',
            //'info:ntext',
            //'area',
            //'keywords',
            //'wflag',
            //'year',
            //'mod_version',
            //'updatetime',
            //'totalDuration',
            //'flag',

            //'voole_id',
            //'price',
            //'is_finish',
            //'yesterday_viewed',
            //,
            //'url:url',
            //'act_img',
            //'download_flag',


        ],
    ]); ?>
    <?php Pjax::begin(); ?>
    <?php Pjax::end(); ?>
    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
