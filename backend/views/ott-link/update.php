<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OttLink */

$this->title = 'Update Ott Link: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Ott Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
