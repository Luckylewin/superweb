<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

$this->title = 'Create Ott Channel';
$this->params['breadcrumbs'][] = ['label' => $mainClass->zh_name, 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = ['label' => $subClass->zh_name, 'url' => Url::to(['sub-class/index', 'main-id' => $mainClass->id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-channel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
