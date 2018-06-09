<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OttEventTeam */

$this->title = 'Update Ott Event Team: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Ott Event Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-event-team-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
