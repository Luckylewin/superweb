<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Vod;
use backend\assets\AppAsset;

AppAsset::register($this);
$this->registerJsFile('/statics/js/pinyin.js');
/* @var $this yii\web\View */
/* @var $model common\models\VodList */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="vod-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $form->field($model, 'list_pid')->textInput() ?>

    <?php $form->field($model, 'list_sid')->textInput() ?>

    <div class="col-md-4">
        <?= $form->field($model, 'list_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4"><?= $form->field($model, 'list_dir')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-4"><?= $form->field($model, 'list_sort')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-4"><?= $form->field($model, 'list_keywords')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-4"><?= $form->field($model, 'list_title')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-4"><?= $form->field($model, 'list_description')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-4"><?= $form->field($model, 'list_ispay')->dropDownList(\backend\blocks\VodBlock::$chargeStatus) ?></div>
    <div class="col-md-4"><?= $form->field($model, 'list_price')->textInput() ?></div>
    <div class="col-md-4"><?= $form->field($model, 'list_trysee')->textInput([ 'placeholder' => 5]) ?></div>
    <div class="col-md-12">
        <?= $form->field($model, 'list_icon')->textInput() ?>

        <?=  \dosamigos\fileupload\FileUploadUI::widget([
            'model' => new \backend\models\UploadForm(),
            'attribute' => 'list_icon',
            'url' => ['upload/image-upload','field' => 'list_icon'],
            'gallery' => false,
            'fieldOptions' => ['accept' => 'image/*'],
            'clientOptions' => ['maxFileSize' => 2000000],
            // ...
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                var files = data.result.files[0];
                                
                                $("#vodlist-list_icon").val(files.path);
                            }',
                'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            ],
        ]); ?>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['vod-list/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>

<?php \common\widgets\Jsblock::begin() ?>
<script>
    $('#vodlist-list_name').blur(function(){
        var spell = Utils.CSpell.getSpell($(this).val(), function(charactor,spell){
            return spell[1];
        });
        $('#vodlist-list_dir').val(spell.replace(',',''));
    });


</script>
<?php \common\widgets\Jsblock::end() ?>
