<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AppMenu */

$this->title = 'Update App Menu: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'App Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="app-menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
