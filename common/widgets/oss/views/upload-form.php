<?php

use common\widgets\Jsblock;
use common\widgets\Cssblock;
use yii\web\AssetBundle as AppAsset;
?>
<?php AppAsset::register($this); ?>
<?php $this->registerJsFile('/statics/js/oss/lib/plupload-2.1.2/js/plupload.full.min.js');?>
<?php $this->registerJsFile('/statics/js/oss/upload.js?v=' . date('Ymd'));?>
<?php JsBlock::begin(['pos' => \yii\web\View::POS_BEGIN]) ?>
<script>
    var mime_types = <?= $mime_types ?>;
    var serverUrl  = '<?= $serverUrl ?>';
    var max_file_size = '<?= $maxSize ?>';
    var field_input_id = '<?= strtolower($model->formName()) . '-'. strtolower($field) ?>';
</script>
<?php JsBlock::end() ?>

<?php Cssblock::begin() ?>
<style>
    .btn-info:hover{
        background-color: #3366b7;
    }
    .progress{
        margin-top:2px;
        width: 280px;
        height: 14px;
        margin-bottom: 10px;
        overflow: hidden;
        background-color: #f5f5f5;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
        box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
    }
    .progress-bar{
        background-color: rgb(92, 184, 92);
        background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.14902) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.14902) 50%, rgba(255, 255, 255, 0.14902) 75%, transparent 75%, transparent);
        background-size: 40px 40px;
        box-shadow: rgba(0, 0, 0, 0.14902) 0px -1px 0px 0px inset;
        box-sizing: border-box;
        color: rgb(255, 255, 255);
        display: block;
        float: left;
        font-size: 12px;
        height: 20px;
        line-height: 20px;
        text-align: center;
        transition-delay: 0s;
        transition-duration: 0.6s;
        transition-property: width;
        transition-timing-function: ease;
        width: 266.188px;
    }
    .uploader {
        border: 1px solid #ccc;
        border-radius : 4px;
        box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075);
        padding: 10px;
    }
</style>
<?php Cssblock::end() ?>

<body>
<div class="uploader">
    <div>
        <?= $form->field($model, $field)->textInput([
            'readonly' => 'true',
            'class' => 'form-control upload-input'
        ]); ?>
    </div>

    <div>
        <?php if($bed): ?>
            <?= \yii\bootstrap\Html::button('阿里云OSS',[
                'class' => 'btn btn-success aliyun'
            ]) ?>
            <?= \yii\bootstrap\Html::button('图床(图标专用)', [
                'class' => 'btn btn-default ouliu'
            ]) ?>
        <?php endif; ?>
    </div>
    <div id="oss" style="display: block">
        <hr>
        <div>
            <input type="radio" name="myradio" value="local_name" checked="checked"/> 保持本地文件名字
            <input type="radio" name="myradio" value="random_name" /> 随机文件名, 后缀保留
        </div>

        <div id="ossfile">你的浏览器不支持flash,Silverlight或者HTML5！</div>
        <br/>
        <div id="container" class="well">
            <?= \yii\helpers\Html::a("选择文件","javascript:void(0);",[
                'id' => "selectfiles",
                'class' => 'btn btn-default'
            ]) ?>
            <?= \yii\helpers\Html::a("开始上传","javascript:void(0);",[
                'id' => "postfiles",
                'class' => 'btn btn-info'
            ]) ?>
        </div>

        <pre id="console" style="display: none;margin-top: 10px;"></pre>
    </div>
</div>
<br>
<?php Jsblock::begin(); ?>

<script>
    //<div> <div class="well"> <form enctype="multipart/form-data" action="http://upload.ouliu.net/" method="POST" class="form-inline"> <div class="form-group"> <label for="inputfile" class="sr-only">文件</label> <input type="file" name="ifile" id="ifile" class="form-controll"/> </div> <input name="submit" type="submit" class="btn btn-info" value="上传" /><br /> </form> </div> 允许最大文件为 (10M)<br/><br/> 支持图片格式为 (<span class="label label-default">png</span> <span class="label label-default">jpg</span> <span class="label label-default">jpeg</span> <span class="label label-default">gif</span> <span class="label label-default">bmp</span> ) </div>
    $('.ouliu').click(function(){
        $('.upload-input').prop('readonly', false);
        $('#oss').hide();
        layer.open({
            type: 2,
            title: '第三方免费图床(http://upload.ouliu.net/)',
            shadeClose: true,
            shade: 0.8,
            area: ['700px', '60%'],
            content: 'http://upload.ouliu.net/' //iframe的url
        });
    });

    $('.aliyun').click(function(){
        $('.upload-input').prop('readonly', true);
        $('#oss').slideDown();
    });

</script>

<?php Jsblock::end(); ?>




