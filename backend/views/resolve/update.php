<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IptvUrlResolution */

$this->title = '更新';
$this->params['breadcrumbs'][] = ['label' => '正则表达式列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->method, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="iptv-url-resolution-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
