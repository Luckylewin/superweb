<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Karaoke */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .preview img {width: 100px;}
</style>
<div class="album-name-karaoke-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-3">
        <?= $form->field($model, 'albumName')->textInput(['rows' => 6]) ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'mainActor')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'area')->dropDownList(\backend\models\Karaoke::getLang()) ?>
    </div>



    <div class="col-md-3">
        <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'year')->textInput() ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'hit_count')->textInput()->label('热度') ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'is_del')->dropDownList(\backend\models\Karaoke::$delStatus) ?>
    </div>

    <div class="col-md-12">

        <?= $form->field($model, 'source')->dropDownList(['youtube' => 'Youtube', 'upload' => '自定义上传']); ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        <div id="upload-video" style="display: none">
            <?= \dosamigos\fileupload\FileUploadUI::widget([
                'model' => new \backend\models\UploadForm(),
                'attribute' => 'media',
                'url' => ['upload/karaoke-upload',],
                'gallery' => false,
                'fieldOptions' => ['accept' => 'video/*'],
                'clientOptions' => ['maxFileSize' => 20000000],
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                         var files = data.result.files[0];
                                         $("#karaoke-url").val(files.path);
                                     }',
                    'fileuploadfail' => 'function(e, data) {
                                         console.log(e);
                                         console.log(data);
                                     }',
                ],
            ]);
            ?>
        </div>


        <?= $form->field($model, 'albumImage')->textInput(['rows' => 6]) ?>

        <?= \dosamigos\fileupload\FileUploadUI::widget([
            'model' => new \backend\models\UploadForm(),
            'attribute' => 'image',
            'url' => ['upload/image-upload',],
            'gallery' => false,
            'fieldOptions' => ['accept' => 'image/*'],
            'clientOptions' => ['maxFileSize' => 2000000],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                         var files = data.result.files[0];
                                         $("#karaoke-albumimage").val(files.path);
                                     }',
                'fileuploadfail' => 'function(e, data) {
                                         console.log(e);
                                         console.log(data);
                                     }',
            ],
        ]);
        ?>
    </div>


    <div class="col-md-12">
        <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>
        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
            <?= Html::a('返回', ['karaoke/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js =<<<JS
    $('#karaoke-source').change(function() {
        if ($(this).val() === 'upload') {
            $('#upload-video').css('display', 'block');
        } else {
            $('#upload-video').css('display', 'none');
        }
    });
JS;
$this->registerJs($js);

?>