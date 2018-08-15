<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUploadUI;
use backend\models\UploadForm;
/* @var $this yii\web\View */
/* @var $model backend\models\SysCountry */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="sys-country-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'zh_name')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'code')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= FileUploadUI::widget([
        'model' => new UploadForm(),
        'attribute' => 'image',
        'url' => ['upload/image-upload',],
        'gallery' => false,
        'fieldOptions' => ['accept' => 'image/*'],
        'clientOptions' => ['maxFileSize' => 2000000],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                     var files = data.result.files[0];
                                     $("#syscountry-icon").val(files.path);
                                 }',
            'fileuploadfail' => 'function(e, data) {
                                     console.log(e);
                                     console.log(data);
                                 }',
        ],
    ]);
    ?>

    <?= $form->field($model, 'icon_big')->textInput(['maxlength' => true]) ?>

    <?= FileUploadUI::widget([
        'model' => new UploadForm(),
        'attribute' => 'image_big',
        'url' => ['upload/image-upload',],
        'gallery' => false,
        'fieldOptions' => ['accept' => 'image/*'],
        'clientOptions' => ['maxFileSize' => 2000000],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                     var files = data.result.files[0];
                                     $("#syscountry-icon_big").val(files.path);
                                 }',
            'fileuploadfail' => 'function(e, data) {
                                     console.log(e);
                                     console.log(data);
                                 }',
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
