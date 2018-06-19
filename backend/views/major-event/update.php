<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MajorEvent */

$this->title = 'Update Major Event';
$this->params['breadcrumbs'][] = ['label' => 'Major Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="major-event-update">

    <div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_update_form', [
        'model' => $model,
        'teams' => $teams
    ]) ?>

</div>
