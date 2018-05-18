<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OttLink */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ott Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-link-view">

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
            'channel_id',
            'link:ntext',
            'source',
            'sort',
            'use_flag',
            'format',
            'script_deal',
            'definition',
            'method',
            'decode',
        ],
    ]) ?>

</div>
