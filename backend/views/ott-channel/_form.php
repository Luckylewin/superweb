<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .preview img {width: 200px;}
</style>

<div class="ott-channel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord): ?>
        <?= $form->field($model, 'sub_class_id')->hiddenInput()->label(false) ?>
    <?php endif; ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zh_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'use_flag')->textInput() ?>

    <?= $form->field($model, 'channel_number')->textInput() ?>

    <?= $form->field($model, 'image')->textInput() ?>

    <?=  \dosamigos\fileupload\FileUploadUI::widget([
        'model' => new \backend\models\UploadForm(),
        'attribute' => 'image',
        'url' => ['upload/image-upload',],
        'gallery' => false,

        'fieldOptions' => [
            'accept' => 'image/*'
        ],

        'clientOptions' => [
            'maxFileSize' => 2000000
        ],

        // ...
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                var files = data.result.files[0];
                              
                                $("#ottchannel-image").val(files.path);
                            }',
            'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        ],
    ]); ?>



    <?= $form->field($model, 'alias_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
