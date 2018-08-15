<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUploadUI;
use backend\models\UploadForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OttEvent */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="ott-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'event_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'event_name_zh')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'event_icon')->textInput(['maxlength' => true]) ?>
        <?= FileUploadUI::widget([
            'model' => new UploadForm(),
            'attribute' => 'image',
            'url' => ['upload/image-upload',],
            'gallery' => false,
            'fieldOptions' => ['accept' => 'image/*' ],
            'clientOptions' => ['maxFileSize' => 2000000],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                     console.log(e);
                                     console.log(data);
                                     var files = data.result.files[0];
                                   
                                     $("#ottevent-event_icon").val(files.path);
                                 }',
                'fileuploadfail' => 'function(e, data) {
                                     console.log(e);
                                     console.log(data);
                                 }',
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'event_icon_big')->textInput(['maxlength' => true]) ?>
        <?= FileUploadUI::widget([
            'model' => new UploadForm(),
            'attribute' => 'image_big',
            'url' => ['upload/image-upload',],
            'gallery' => false,
            'fieldOptions' => ['accept' => 'image/*' ],
            'clientOptions' => ['maxFileSize' => 2000000],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                     console.log(e);
                                     console.log(data);
                                     var files = data.result.files[0];
                                   
                                     $("#ottevent-event_icon_big").val(files.path);
                                 }',
                'fileuploadfail' => 'function(e, data) {
                                     console.log(e);
                                     console.log(data);
                                 }',
            ],
        ]); ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'event_introduce')->textarea(['rows' => 8]) ?>

        <?= $form->field($model, 'sort')->textInput(['placeholder' => '0']) ?>

        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['ott-event/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
