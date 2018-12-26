<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\VodProfiles */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Vod Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-profiles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'alias_name',
            'screen_writer',
            'director',
            'actor',
            'area',
            'language',
            'type',
            'tag',
            'user_tag',
            'plot:ntext',
            'year',
            'date',
            'imdb_id',
            'imdb_score',
            'tmdb_id',
            'tmdb_score',
            'douban_id',
            'douban_score',
            'douban_voters',
            'length',
            'cover',
            'image',
            'banner',
            'comment:ntext',
            'fill_status',
            'douban_search',
            'imdb_search',
            'tmdb_search',
            'profile_language',
            'media_type',
        ],
    ]) ?>

</div>
