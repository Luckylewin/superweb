<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\OssUploader;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model backend\models\ApkDetail */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="apk-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord): ?>
    <?= $form->field($model, 'apk_ID')->hiddenInput([
            'value' => $apkModel->ID
        ])->label(false); ?>
    <?php endif; ?>

    <?= $form->field($model, 'ver')->textInput(['maxlength' => true]) ?>




    <?= $form->field($model, 'url')->textInput() ?>

    <?=  FileUploadUI::widget([
        'model' => new \backend\models\UploadForm(),
        'attribute' => 'apk',
        'url' => ['upload/apk-upload'],
        'gallery' => false,
        'fieldOptions' => [
            'accept' => 'apk'
        ],
        'clientOptions' => [
            'maxFileSize' => 200000000
        ],
        // ...
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                var files = data.result.files[0];
                               
                                $("#apkdetail-url").val(files.path);
                                $("#apkdetail-md5").val(files.md5);
                            }',
            'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        ],
    ]); ?>

    <?= $form->field($model, 'md5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 3]) ?>



    <?= $form->field($model, 'force_update')->dropDownList(['0' => Yii::t('backend', 'No'), '1' => Yii::t('backend', 'Yes')]) ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>

            <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['apk-list/index']), ['class' => 'btn btn-default']) ?>


    </div>

    <?php ActiveForm::end(); ?>

</div>
