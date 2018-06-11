<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mac */

$this->title = 'Update Mac: ' . $model->MAC;
$this->params['breadcrumbs'][] = ['label' => 'Macs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->MAC, 'url' => ['view', 'id' => $model->MAC]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mac-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
