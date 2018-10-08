<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OttEventTeam */

$this->params['breadcrumbs'][] = ['label' => 'Ott Event Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->team_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-event-team-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
