<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\OssUploader;

/* @var $this yii\web\View */
/* @var $model backend\models\ApkDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apk-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord): ?>
    <?= $form->field($model, 'apk_ID')->hiddenInput([
            'value' => $apkModel->ID
        ])->label(false); ?>
    <?php endif; ?>

    <?= $form->field($model, 'ver')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'md5')->textInput(['maxlength' => true]) ?>


    <?= OssUploader::widget([
        'model' => $model,
        'dir' => $model->dir . $model->isNewRecord? $apkModel->typeName : $model->apkName->typeName . '/',
        'form' => $form,
        'field' => 'url',
        'allowExtension' => [
            'apk' => 'application/vnd.android.package-archive,.apk,apk'
        ]
    ]); ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'force_update')->dropDownList(['0' => '否', '1' => '是']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>

        <?= Html::a('返回', \yii\helpers\Url::to(['apk-detail/index', 'id' => $model->apk_ID]), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
