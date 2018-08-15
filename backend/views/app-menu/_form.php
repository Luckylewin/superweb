<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \backend\models\UploadForm;
use \backend\models\AppMenu;
/* @var $this yii\web\View */
/* @var $model backend\models\AppMenu */
/* @var $form yii\widgets\ActiveForm */
$uploadForm = new UploadForm();
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
    img {
        max-width: 70px;
    }
</style>
<div class="app-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'zh_name')->textInput() ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
        <?=  \dosamigos\fileupload\FileUploadUI::widget([
            'model' => $uploadForm,
            'attribute' => 'image',
            'url' => ['upload/image-upload','field' => 'image'],
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
                              
                                $("#appmenu-icon").val(files.path);
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
            'model' => $uploadForm,
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
                              
                                $("#appmenu-icon_hover").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'icon_big')->textInput(['maxlength' => true]) ?>
        <?=  \dosamigos\fileupload\FileUploadUI::widget([
            'model' => $uploadForm,
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
                              
                                $("#appmenu-icon_big").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'icon_big_hover')->textInput(['maxlength' => true]) ?>
        <?=  \dosamigos\fileupload\FileUploadUI::widget([
            'model' => $uploadForm,
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
                              
                                $("#appmenu-icon_big_hover").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'restful_url')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'auth')->dropDownList(AppMenu::$isAuth) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
