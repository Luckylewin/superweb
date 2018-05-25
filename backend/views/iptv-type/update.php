<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IptvType */

$this->title = 'Update Iptv Type: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Iptv Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="iptv-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
