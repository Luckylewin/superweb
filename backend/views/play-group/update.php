<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayGroup */

$this->title = 'Update Play Group: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Play Groups', 'url' => Yii::$app->request->referrer];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="play-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
