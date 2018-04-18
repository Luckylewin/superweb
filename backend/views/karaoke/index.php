<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\query\AlbumNameKaraokeQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '卡拉OK视频';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-name-karaoke-index">


    <?php Pjax::begin(); ?>
    <?php //$this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                        return Html::a($model->url, "https://www.youtube.com/watch?v=" . $model->url, [
                                'target' => '_blank'
                        ]);
                    }
            ],

            [
                    'attribute' => 'hit_count',
                    'label' => '热度'
            ],

            [
                    'attribute' => 'area',
                    'filter' => \backend\models\Karaoke::getLang()

            ],

            [
                    'attribute' => 'is_del',
                    'filter' => \backend\models\Karaoke::$delStatus,
                    'value' => function($model) {
                        return $model->status;
                    }
            ],

            'utime:date',

            [
                'class' => 'common\grid\MyActionColumn',
                'header' => '操作'
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

    <?php Pjax::end(); ?>
    <p>
        <?= Html::a('新增视频', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
