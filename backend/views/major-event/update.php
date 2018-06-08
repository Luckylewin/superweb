<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MajorEvent */

$this->title = 'Update Major Event: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Major Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="major-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
