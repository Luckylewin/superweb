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
<div class="vod-profiles-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Vod Profiles'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            'douban_score',
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
                    'visibleButtons' => [
                            'delete' => Yii::$app->user->can('profiles/delete')
                    ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
