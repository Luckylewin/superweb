<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OttAccess */


$this->params['breadcrumbs'][] = ['label' => 'Ott Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mac, 'url' => ['view', 'mac' => $model->mac, 'genre' => $model->genre]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-access-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
