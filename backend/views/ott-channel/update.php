<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => '频道列表', 'url' => \yii\helpers\Url::to(['ott-channel/index', 'sub-id' => $model->subClass->id])];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="ott-channel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
