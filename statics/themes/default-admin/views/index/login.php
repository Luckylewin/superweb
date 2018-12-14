<?php
use yii\bootstrap\BootstrapAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\Alert;

BootstrapAsset::register($this);
$this->title = '管理员登录';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="robots" content="NOINDEX,NOFOLLOW,noarchive" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="/statics/themes/default-admin/css/login.css?v=1" rel="stylesheet">
</head>
<style>
    input{font-size: 20px;font-weight: bold;color: #0d8ddb}
    #captcha{border-radius: 3px;height: 42px;margin-right: 10px;}

</style>
<body class="login-body">
<?php $this->beginBody() ?>

<div class="container">
    <?= Alert::widget() ?>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-signin'],
    ]);?>

    <div class="form-signin-heading text-center">
        <h1 class="sign-title">Sign In</h1>
        <img src="/statics/themes/default-admin/images/login-logo.png" alt=""/>
    </div>
    <div class="login-wrap">
        <?= $form->field($model, 'username', ['template' => '<div class="form-group field-loginform-password required">{input}{hint}{error}</div>'])->textInput(['autofocus' => true, 'placeholder' => '用户名']) ?>
        <?= $form->field($model, 'password', ['template' => '<div class="form-group field-loginform-password required">{input}{hint}{error}</div>'])->passwordInput(['placeholder' => '密码']) ?>

        <?= \yii\captcha\Captcha::widget([
            'model'         => $model,
            'attribute'     => 'verifyCode',
            'captchaAction' => 'site/captcha',
            'options'       => [
                    'placeholder'=>'验证码',
                    'class' => 'form-control',
                    'style' => 'width:130px;display:inline;font-size:14px;font-weight:bold;text-align:center'
            ],
            'imageOptions'  => ['id'=>'captcha'],
            'template'      => '{image}{input}',
            'id' => 'captcha',
        ]);?>

        <?= Html::submitButton('<i class="fa fa-check"></i>', ['class' => 'btn btn-lg btn-login btn-block', 'name' => 'login-button']) ?>

        <label class="checkbox"><?= $form->field($model, 'rememberMe')->checkbox() ?></label>

    </div>
    <?php ActiveForm::end();?>
</div>
<?php $this->endBody() ?>
</body>
</html>

<?php

$requestUrl = \yii\helpers\Url::to(['site/login']);

$js =<<<JS
    
     $("#captcha").click(function(){
        var url = "<?= \yii\helpers\Url::to(['site/captcha']);?>";
        $.get(url,{refresh:1},function(data){
          var object = eval(data);
          $('#captcha').attr('src',object.url);
        })
    });
  
    $(document).on('beforeSubmit', 'form#login-form', function () {
        var form = $(this); 
        //返回错误的表单信息 
        if (form.find('.has-error').length) { 
            return false; 
        } 
        //表单提交 
        $.ajax({ 
          url  : form.attr('action'), 
          type  : 'post', 
          data  : form.serialize(), 
          beforeSend : function(){
              var index = layer.load(1, {shade: [0.1,'#fff']});
          },
          success: function (response){ 
             if (response.status === 'success') {
                 layer.msg(response.status);
                 window.location.reload(); 
             } else {
                 layer.closeAll("loading");
                 layer.msg(response.msg);
             }
          }, 
          error : function (){ 
            layer.alert('系统错误', {icon:5}); 
            return false; 
          } 
        }); 
        return false; 
  }); 
JS;

$this->registerJs($js,\yii\web\View::POS_END);

?>
<?php $this->endPage() ?>
<script src="/statics/themes/default-admin/plugins/layer/layer.min.js"></script>



