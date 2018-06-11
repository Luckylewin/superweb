<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IptvTypeItem */

$this->title = 'Update Iptv Type Item: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Iptv Type Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="iptv-type-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
