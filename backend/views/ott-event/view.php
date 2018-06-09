<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OttEvent */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ott Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-event-view">

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
            'event_name',
            'event_name_zh',
            'event_introduce',
            'event_icon',
            'event_icon_big',
            'sort',
        ],
    ]) ?>

</div>
