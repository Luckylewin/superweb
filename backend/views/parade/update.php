<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Parade */

$this->title = 'Update Parade: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Parades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parade-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
