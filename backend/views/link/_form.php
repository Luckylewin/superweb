<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vodlink */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/statics/js/big-file-uploader/common.js', ['depends' => 'yii\web\JqueryAsset']);
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="vodlink-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'myForm',
        'options' => [
            'enctype' => 'multipart/form-data',
            'style' => 'display:none'
        ]
    ]); ?>

    <div class="col-md-12">
        <div class="progress progress-striped active" style="display: none">
            <div class="progress-bar progress-bar-success" role="progressbar"
                 aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                 style="width: 0;">
                <span class="sr-only">0% 未开始</span>
            </div>
        </div>
        <table class="table" id="upload-list" >
            <thead>
            <tr>
                <th>文件名</th>
                <th>类型</th>
                <th>大小</th>
                <th>进度</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>待上传</td><td>mp4</td><td>-</td><td>-</td></tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="myFile">文件上传</label>
            <?= Html::fileInput('file',null, [
                'id' => 'myFile'
            ]) ?>
            <div class="help-block"></div>
        </div>
    </div>


    <?php ActiveForm::end() ?>
    </div>

    <?php $form = ActiveForm::begin(); ?>
    <div >
        <div class="col-md-6">
        <?= $form->field($model, 'save_type')->dropDownList(\common\models\Vodlink::$saveTypeMap); ?>

        <?php if($model->isNewRecord): ?>
           <?= $form->field($model, 'group_id')->hiddenInput()->label(false); ?>
        <?php endif; ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'episode')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'hd_url')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
        <?= $form->field($model, 'plot')->textarea(['rows'=>3]) ?>
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>

                <?= Html::a(Yii::t('backend','Go Back'), Yii::$app->request->referrer, [
                    'class' => 'btn btn-default'
                ]) ?>

            </div>
        </div>


    </div>

    <?php ActiveForm::end(); ?>


<?php
$uploadUrl = \yii\helpers\Url::to(['upload/chunk-upload']);
$uploadJS=<<<JS
    
    UP.__hook({
        before:function() {
            $('.progress').show();
        }, 
        process:function(callback) {
            $('.progress-bar').css('width', callback.progress);
            $('.sr-only').text(callback.progress + '% 完成');
        },
        success:function(callback) {
             $('.progress-bar').css('width', '100%');
             $('#vodlink-url').val(callback.path);
              $('.progress').slideUp(1500);
        },
        fail:function(callback) {
             
        }  
    });

    UP.__init({
            myFile: "#myFile", //fileInput节点
            ServerUrl:"{$uploadUrl}",//服务器地址
            eachSize:250000 //分片大小
        });
    
    $('#vodlink-save_type').change(function() {
        var val = $(this).val();
        if (val == 'server') {
            $('#myForm').show();
        } else {
            $('#myForm').hide();
        }
    })
    
JS;
$this->registerJs($uploadJS, $this::POS_END);
?>

<script type="text/template" id="file-upload-tpl">
    <tr>
        <td>{{fileName}}</td>
        <td>{{fileType}}</td>
        <td>{{fileSize}}</td>
        <td class="upload-progress">{{progress}}</td>
        <td>
            <input type="button" class="upload-item-btn btn btn-info"  data-name="{{fileName}}" data-size="{{totalSize}}" data-state="default" value="{{uploadVal}}">
        </td>
    </tr>
</script>


