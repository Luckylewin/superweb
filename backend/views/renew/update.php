<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RechargeCard */

$this->title = 'Update Recharge Card: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Recharge Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->card_num, 'url' => ['view', 'id' => $model->card_num]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="recharge-card-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
