<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vodlink */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/statics/js/big-file-uploader/common.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<div class="vodlink-form">

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
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'id' => 'myForm',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]); ?>

    <div class="col-md-6">
        <div class="form-group well">
            <label for="myFile">文件上传</label>
            <?= Html::fileInput('file',null, [
                'id' => 'myFile'
            ]) ?>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="col-md-6"></div>


    <div class="col-md-6">
        <table class="table" id="upload-list" style="width:100%;display: none">
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
            </tbody>
        </table>
    </div>

    <div class="col-md-12">
        <div class="progress progress-striped active" style="display: none">
            <div class="progress-bar progress-bar-success" role="progressbar"
                 aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                 style="width: 0;">
                <span class="sr-only">0% 未开始</span>
            </div>
        </div>
    </div>


        <?php ActiveForm::end() ?>
    </div>


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
            eachSize:10240000 //分片大小
        });
    
JS;
$this->registerJs($uploadJS, $this::POS_END);
?>



    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">

        <?php if($model->isNewRecord): ?>
           <?= $form->field($model, 'video_id')->dropDownList([$vod->vod_id => $vod->vod_name]); ?>
        <?php endif; ?>

        <?= $form->field($model, 'episode')->textInput() ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'hd_url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'plot')->textarea(['rows'=>6]) ?>

        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>

            <?php if($model->isNewRecord): ?>
                <?= Html::a(Yii::t('backend','Go Back'), Yii::$app->request->referrer, [
                        'class' => 'btn btn-default'
                ]) ?>
            <?php else: ?>
                <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['link/index', 'vod_id' => $model->vodInfo->vod_id]), [
                    'class' => 'btn btn-default'
                ]) ?>
            <?php endif; ?>

        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>


