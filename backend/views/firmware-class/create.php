<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FirmwareClass */

$this->title = 'Create Firmware Class';
$this->params['breadcrumbs'][] = ['label' => 'Firmware Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmware-class-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
