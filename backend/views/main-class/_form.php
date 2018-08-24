<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MainClass */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
    .preview img {width: 200px;}
</style>

<div class="main-class-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'list_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'zh_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'is_charge')->dropDownList([Yii::t('backend', 'Free'), Yii::t('backend', 'Charge')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'price'); ?>
    </div>

    <div class="col-md-12">



        <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    </div>


    <div class="col-md-6">
        <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

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
                              
                                $("#mainclass-icon").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'icon_hover')->textInput(['maxlength' => true]) ?>

        <?=  \dosamigos\fileupload\FileUploadUI::widget([
            'model' => new \backend\models\UploadForm(),
            'attribute' => 'image_hover',
            'url' => ['upload/image-upload','field' => 'image_hover'],
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
                              
                                $("#mainclass-icon_hover").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'icon_bg')->textInput(['maxlength' => true]) ?>

        <?=  \dosamigos\fileupload\FileUploadUI::widget([
            'model' => new \backend\models\UploadForm(),
            'attribute' => 'image_big',
            'url' => ['upload/image-upload','field' => 'image_big'],
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
                              
                                $("#mainclass-icon_bg").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'icon_bg_hover')->textInput(['maxlength' => true]) ?>

        <?=  \dosamigos\fileupload\FileUploadUI::widget([
            'model' => new \backend\models\UploadForm(),
            'attribute' => 'image_big_hover',
            'url' => ['upload/image-upload','field' => 'image_big_hover'],
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
                              
                                $("#mainclass-icon_bg_hover").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= $form->field($model, 'use_flag')->dropDownList([Yii::t('backend', 'off'), Yii::t('backend', 'on')]); ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
