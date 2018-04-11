<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ApkDetail */

$this->title = "新增";
$this->params['breadcrumbs'][] = ['label' => 'Apk列表', 'url' => \yii\helpers\Url::to(['apk-list/index'])];
$this->params['breadcrumbs'][] = ['label' => 'Apk版本列表', 'url' => \yii\helpers\Url::to(['apk-detail/index','id' => Yii::$app->request->get('apk_ID')])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-detail-create">

    <?= $this->render('_form', [
        'model' => $model,
        'apkModel' => $apkModel
    ]) ?>

</div>
