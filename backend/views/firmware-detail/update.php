<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FirmwareDetail */

$this->title = 'Update Firmware Detail: ';
$this->params['breadcrumbs'][] = ['label' => 'Firmware Details', 'url' => ['index', 'firmware_id'=> $model->firmware_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="firmware-detail-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
