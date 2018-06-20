<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DvbOrder */

$this->title = 'Update Dvb Order: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Dvb Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dvb-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
