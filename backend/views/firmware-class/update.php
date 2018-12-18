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


    <?= $this->render('_form', [
        'model' => $model,
        'dropDownList' => $dropDownList
    ]) ?>

</div>
