<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Karaoke */

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '卡拉ok列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->albumName, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="album-name-karaoke-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
