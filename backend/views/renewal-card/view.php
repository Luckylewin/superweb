<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RenewalCard */

$this->title = $model->card_num;
$this->params['breadcrumbs'][] = ['label' => 'Renewal Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renewal-card-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->card_num], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->card_num], [
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
            'card_num',
            'card_secret',
            'card_contracttime',
            'is_del',
            'is_valid',
            'created_time:datetime',
            'updated_time:datetime',
            'batch',
            'who_use',
        ],
    ]) ?>

</div>
