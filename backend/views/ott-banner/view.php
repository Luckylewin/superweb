<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OttBanner */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Ott Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-banner-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'desc',
            'image',
            'image_big',
            'sort',
            'channel_id',
            'url:url',
        ],
    ]) ?>

</div>
