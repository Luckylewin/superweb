<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mac */

$this->title = $model->MAC;
$this->params['breadcrumbs'][] = ['label' => 'Macs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mac-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->MAC], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->MAC], [
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
            'MAC',
            'SN',
            'use_flag',
            'ver',
            'regtime',
            'logintime',
            'type',
            'duetime',
            'contract_time',
        ],
    ]) ?>

</div>
