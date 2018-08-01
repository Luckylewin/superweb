<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \dosamigos\fileupload\FileUploadUI;
use backend\models\UploadForm;
use common\models\OttChannel;
/* @var $this yii\web\View */
/* @var $model backend\models\OttBanner */
/* @var $form yii\widgets\ActiveForm */

$channel = OttChannel::findOne($model->channel_id);
$model->title = $channel->name;
$model->desc = $channel->name;
if ($model->isNewRecord) {
    $model->sort = 0;
}

?>


<style>
    .preview img {width: 200px;}
</style>

<div class="ott-banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
            <?= $form->field($model, 'channel_id')->dropDownList([
                $model->channel_id => $channel->name
            ]); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'desc')->textarea(['rows' => 5]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

            <?=  FileUploadUI::widget([
                'model' => new UploadForm(),
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
                              
                                $("#ottbanner-image").val(files.path);
                            }',
                    'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
                ],
            ]); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'image_big')->textInput(['maxlength' => true]) ?>

            <?=  FileUploadUI::widget([
                'model' => new UploadForm(),
                'attribute' => 'image_big',
                'url' => ['upload/image-upload','field' => 'image_big'],
                'gallery' => false,
                'fieldOptions' => ['accept' => 'image/*'],
                'clientOptions' => ['maxFileSize' => 2000000],
                // ...
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                var files = data.result.files[0];
                                $("#ottbanner-image_big").val(files.path);
                            }',
                    'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
                ],
            ]); ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('backend','Go Back'), ['main-class/index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

