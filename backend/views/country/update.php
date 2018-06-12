<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SysCountry */

$this->title = 'Update Sys Country';
$this->params['breadcrumbs'][] = ['label' => 'Sys Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sys-country-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
