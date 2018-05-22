<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MainClass */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .preview img {width: 200px;}
</style>

<div class="main-class-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zh_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
