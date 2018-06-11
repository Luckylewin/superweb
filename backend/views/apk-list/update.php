<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApkList */

$this->title = '更新';
$this->params['breadcrumbs'][] = ['label' => 'Apk列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->typeName, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
