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

    <div class="col-md-12">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'id' => 'myForm',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]); ?>

        <table class="table table-bordered" id="upload-list">
            <caption>
                <?= Html::fileInput('file',null, [
                    'id' => 'myFile'
                ]) ?>
            </caption>
            <thead>
                <tr>
                    <th>文件名</th>
                    <th>文件类型</th>
                    <th>文件大小</th>
                    <th>上传进度</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?= Html::button('上传',[
                        'id' => 'upload-all-btn',
                        'class' => 'btn btn-info'
                    ]) ?>
                </td>
            </tr>

            </tbody>
        </table>
        <?php ActiveForm::end() ?>
    </div>

<?php
    $uploadUrl = \yii\helpers\Url::to(['upload/chunk-upload']);
    $uploadJS=<<<JS
    
    UP.afterSuccess = function(callback){
        $('#vodlink-url').val(callback.path);
    };
    UP.__init({
            myFile: "#myFile", //fileinput节点
            ServerUrl:"{$uploadUrl}",//服务器地址
            eachSize:10240000 //分片大小
        });
JS;
    $this->registerJs($uploadJS, $this::POS_END);
?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'video_id')->dropDownList([$vod->vod_id => $vod->vod_name]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'episode')->textInput() ?>
    </div>

    <div class="col-md-12">

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'hd_url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'plot')->textarea(['rows'=>6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
