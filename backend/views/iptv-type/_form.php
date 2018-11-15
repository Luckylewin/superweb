<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model backend\models\IptvType */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="iptv-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field')->dropDownList([
            'year'      => 'year',
            'type'      => 'type',
            'language'  => 'language',
            'area'      => 'area',
            'hot'       => 'hot',
    ]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= FileUploadUI::widget([
        'model' => new \backend\models\UploadForm(),
        'attribute' => 'image',
        'url' => ['upload/image-upload',],
        'gallery' => false,
        'fieldOptions' => ['accept' => 'image/*'],
        'clientOptions' => ['maxFileSize' => 2000000],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                     var files = data.result.files[0];
                                     $("#iptvtype-image").val(files.path);
                                 }',
            'fileuploadfail' => 'function(e, data) {
                                     console.log(e);
                                     console.log(data);
                                 }',
        ],
    ]);
    ?>

    <?= $form->field($model, 'image_hover')->textInput(['maxlength' => true]) ?>

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
                              
                                $("#iptvtype-image_hover").val(files.path);
                            }',
            'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        ],
    ]); ?>

    <?php if($model->isNewRecord): ?>
        <?= $form->field($model, 'vod_list_id')->hiddenInput()->label(false); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend', 'Go Back'), Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
