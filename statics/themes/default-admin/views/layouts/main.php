<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\assets\BootstrapPluginAsset;
use yii\widgets\Breadcrumbs;
BootstrapPluginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= isset(Yii::$app->params['basic']['sitename']) ? Yii::$app->params['basic']['sitename'] . ' - ' : '' . Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php $this->registerCssFile('/statics/themes/default-admin/css/style.css?v=4.1.0') ?>
    <?php $this->registerCssFile('/statics/themes/default-admin/css/font-awesome.min.css?v=4.4.0',['depends'=>['yii\bootstrap\BootstrapAsset']]) ?>
    <?php $this->registerCssFile('/statics/plugins/page/pace-blue-theme-flash.css',['depends'=>['yii\bootstrap\BootstrapAsset']]) ?>
    <?php $this->registerJsFile('/statics/plugins/page/page.min.js', ['depends'=>['yii\web\JqueryAsset']]); ?>
    <?php $this->registerJsFile('/statics/themes/default-admin/plugins/toastr/toastr.min.js');?>
    <?php $this->registerCssFile('/statics/themes/default-admin/plugins/toastr/toastr.min.css'); ?>
<body>
<?php $this->beginBody() ?>

<div class="wrapper" >
    <div class="panel">
        <div class="panel-body">
            <div class="col-md-12">
                <!-- 面包屑导航 -->
                <div>
                    <?php
                        try {
                           echo Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                'homeLink' => false
                            ]);
                        } catch (\Exception $e) {

                        }
                    ?>
                </div>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
<?php

$js=<<<JS
    
JS;

?>
<script>
  $(function(){
    toastr.options = {
      "closeButton": true,
      "debug": true,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "showDuration": "400",
      "hideDuration": "1000",
      "timeOut": "4000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "slideDown",
      "hideMethod": "slideUp"
    }
  });

    $(document).keyup(function(event){
        if (event.keyCode === 27 || event.keyCode === 96) {
            layer.closeAll();
        }
    });

  $(function() {
    window.layer = parent.window.getCommonLayer();
    window.layer.myWindows = function (url, title,width='1124px',height='600px') {

      //var side = window.parent.document.getElementById('side-menu');
      var top = '100px';
      var left = '220px';

      layer.open({
        title:title,
        type: 2,
        area: [width, height],
        offset: [top, left],
        anim: 2,
        fixed: true, //不固定
        maxmin: true,
        content: url
      });
    }
  });

 /*

  var frameId = window.frameElement && window.frameElement.id || '';

 $(document).keyup(function(event){
    if (event.keyCode === 27 || event.keyCode === 96) {
      var last_page = document.referrer;
      if (last_page.indexOf(window.location.host) != -1) {
        window.location.href = last_page;
      }
    }
  });*/

</script>
<?= \common\widgets\Toastr::widget();?>
</html>
<?php $this->endPage() ?>





