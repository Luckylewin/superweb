<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FirmwareClass */

$this->title = 'Update Firmware Class: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Firmware Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="firmware-class-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
