<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FirmwareDetail */

$this->title = 'Create Firmware Detail';
$this->params['breadcrumbs'][] = ['label' => 'Firmware Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmware-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
