<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MajorEvent */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Major Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-event-view">

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
            'time:datetime',
            'title',
            'live_match',
            'base_time:datetime',
            'match_data',
            'sort',
        ],
    ]) ?>

</div>
