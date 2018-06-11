<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OttEventTeam */

$this->title = 'Create Ott Event Team';
$this->params['breadcrumbs'][] = ['label' => 'Ott Event Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-event-team-create">

    <div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
