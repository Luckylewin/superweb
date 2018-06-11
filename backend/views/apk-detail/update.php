<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApkDetail */

$this->params['breadcrumbs'][] = ['label' => 'Apk列表', 'url' => \yii\helpers\Url::to(['apk-list/index'])];
$this->params['breadcrumbs'][] = ['label' => $model->apkName->typeName, 'url' => \yii\helpers\Url::to(['apk-detail/index','id' => $model->apkName->ID])];
$this->params['breadcrumbs'][] = ['label' => $model->ver];

?>
<div class="apk-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'apkModel' => $apkModel
    ]) ?>

</div>
