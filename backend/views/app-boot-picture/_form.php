<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Jsblock;

/* @var $this yii\web\View */
/* @var $model backend\models\AppBootPicture */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']);
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
    .preview img {width: 160px;}
</style>
<div class="app-boot-picture-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'boot_pic')->textInput(['maxlength' => true]) ?>

    <?= \dosamigos\fileupload\FileUploadUI::widget([
        'model' => new \backend\models\UploadForm(),
        'attribute' => 'image',
        'url' => ['upload/image-upload',],
        'gallery' => false,
        'fieldOptions' => ['accept' => 'image/*'],
        'clientOptions' => ['maxFileSize' => 6000000],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                     var files = data.result.files[0];
                                     $("#appbootpictureblock-boot_pic").val(files.path);
                                 }',
            'fileuploadfail' => 'function(e, data) {
                                     console.log(e);
                                     alert(data);
                                 }',
        ],
    ]);
    ?>


    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'during')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\backend\blocks\AppBootPictureBlock::getStatus()) ?>

    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Submit'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['app-boot-picture/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php Jsblock::begin() ?>
<script>
  laydate.render({
    elem: '#appbootpictureblock-during',
    range: true
  });
 
</script>
<?php Jsblock::end() ?>
