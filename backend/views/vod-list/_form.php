<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Vod;

$this->registerJsFile('/statics/js/pinyin.js');
/* @var $this yii\web\View */
/* @var $model common\models\VodList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vod-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $form->field($model, 'list_pid')->textInput() ?>

    <?php $form->field($model, 'list_sid')->textInput() ?>

    <?= $form->field($model, 'list_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'list_dir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'list_sort')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'list_keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'list_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'list_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'list_ispay')->dropDownList(Vod::$chargeStatus) ?>

    <?= $form->field($model, 'list_price')->textInput() ?>

    <?= $form->field($model, 'list_trysee')->textInput([
            'placeholder' => 5
    ]) ?>

    <?= $form->field($model, 'list_icon')->textInput() ?>
    <?=  \dosamigos\fileupload\FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'icon',
        'url' => ['media/image-upload', 'attr' => 'icon', 'dir' => 'vod-type'],
        'gallery' => false,
        'fieldOptions' => [
            'accept' => 'image/*'
        ],
        'clientOptions' => [
            'maxFileSize' => 2000000,
            'style'=>'width:200px;'
        ],
        // ...
        'clientEvents' => [
            'filedeletedone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                
                             
                               alert();
                            }',
            'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                var files = data.result.files[0];
                             
                                $("#vodlist-list_icon").val(files.url);
                            }',
            'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        ],
    ]); ?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回', ['vod-list/index'], ['class' => 'btn btn-default']) ?>
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
