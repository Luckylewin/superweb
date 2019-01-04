<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\VodProfilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Vod Profiles');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .great {background: deepskyblue;color: #fff;}
    .nice {background: skyblue;color: #fff;}
    .soso {background: lightskyblue;color: #fff;}
    .not-bad {background: lightblue;color: #fff;}
    .bad {background: lightsteelblue;color: #fff;}
</style>

<div class="vod-profiles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Vod Profiles'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Yii::$app->formatter->nullDisplay = '暂无'; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => 'common\widgets\goPager',
            'firstPageLabel' => Yii::t('backend', 'First Page'),
            'lastPageLabel' => Yii::t('backend', 'Last Page'),
            'go' => true
        ],

        'columns' => [
            //'cover:image',
            'name',
            'type',

            [
                'attribute' => 'area',
                'headerOptions' => ['class' => 'col-md-1']
            ],
            [
                'attribute' => 'media_type',
                'filter' => [ 'movie' => 'movie',
                              'serial' => 'serial',
                              'cartoon' => 'cartoon',
                              'variety' => 'variety'],
                'headerOptions' => ['class' => 'col-md-1']
            ],

            [
                'attribute' => 'douban_score',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!is_null($model->douban_score) && is_numeric($model->douban_score)) {
                        $point = $model->douban_score * 10;
                        if ($point > 90) {
                            return Html::button($model->douban_score,['class' => 'btn great btn-sm']);
                        } else if ($point > 80) {
                            return Html::button($model->douban_score,['class' => 'btn nice btn-sm']);
                        } else if ($point > 70) {
                            return Html::button($model->douban_score,['class' => 'btn soso btn-sm']);
                        } else if ($point > 60) {
                            return Html::button($model->douban_score,['class' => 'btn not-bad btn-sm']);
                        } else {
                            return Html::button($model->douban_score,['class' => 'btn bad btn-sm']);
                        }
                    }
                },
                'headerOptions' => ['class' => 'col-md-1']
            ],
            [
                    'attribute' => 'fill_status',
                    'value' => function($model) {
                        return $model->getFillStatus();
                    },
                    'headerOptions' => ['class' => 'col-md-1']
            ],
            //'alias_name',
            //'cover',
            //'screen_writer',
            //'director',
            //'actor',
            //'area',
            //'language',
            //,
            //'tag',
            //'user_tag',
            //'plot:ntext',
            //'year',
            //'date',
            //'imdb_id',
            //'imdb_score',
            //'tmdb_id',
            //'tmdb_score',
            //'douban_id',
            //'douban_score',
            //'douban_voters',
            //'length',
            //'image',
            //'banner',
            //'comment:ntext',
            //'fill_status',
            //'douban_search',
            //'imdb_search',
            //'tmdb_search',
            //'profile_language',
            //'media_type',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'buttons' => [
                            'view' => function($url, $model) {
                                return \common\widgets\frameButton::widget([
                                    'url' => $url,
                                    'content' => Yii::t('backend', 'View'),
                                    'options' => ['class' => 'btn btn-info btn-sm']
                                ]);
                            }
                    ],
                    'visibleButtons' => [
                            'delete' => Yii::$app->user->can('profiles/delete')
                    ]
            ],
        ],
    ]); ?>

</div>
