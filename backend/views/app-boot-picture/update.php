<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AppBootPicture */

$this->title = 'Update App Boot Picture: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'App Boot Pictures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="app-boot-picture-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
