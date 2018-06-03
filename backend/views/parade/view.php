<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Parade */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parade-view">

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
            'channel_name',
            'parade_date',
            'upload_date',
            'parade_data:ntext',
        ],
    ]) ?>

</div>
