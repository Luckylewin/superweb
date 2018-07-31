<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\oss\UploadWidget;
use common\widgets\md5\HashWidget;
/* @var $this yii\web\View */
/* @var $model backend\models\FirmwareDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firmware-detail-form">

    <?php $form = ActiveForm::begin(); ?>



    <div class="col-md-6">
        <?= $form->field($model, 'ver')->textInput(['maxlength' => true]) ?>
    </div>


    <div class="col-md-6">
        <?= HashWidget::widget([
            'model' => $model,
            'form' => $form,
            'field' => 'md5'
        ]); ?>
    </div>

    <div class="col-md-12">
        <?= UploadWidget::widget([
            'model' => $model,
            'dir' => 'dvb/' . $model->firmware->name . '/',
            'form' => $form,
            'field' => 'url',
            'allowExtension' => [
                'apk' => 'application/octet-stream,.bin,bin,.zip,zip'
            ]
        ]); ?>

        <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'sort')->textInput([
                'value' => $model->isNewRecord ? '0' : $model->sort
        ]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'force_update')->dropDownList([
                '0' => '不强制',
                '1' => '强制更新'
        ]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'is_use')->dropDownList([
                '1' => '可用',
                '0' => '不可用'
        ]) ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton('新增', ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['firmware-class/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>




