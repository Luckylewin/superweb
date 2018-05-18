<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

$this->title = 'Update Ott Channel: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => $mainClass->zh_name, 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = ['label' => $subClass->zh_name, 'url' => Url::to(['sub-class/index', 'main-id' => $mainClass->id])];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-channel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
