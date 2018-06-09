<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OttEvent */

$this->title = 'Update Ott Event: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Ott Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
