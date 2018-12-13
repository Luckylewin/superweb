<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\BootstrapPluginAsset;

BootstrapPluginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php $this->head() ?>

    <link rel="shortcut icon" href="/statics/iptv.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title><?= isset(Yii::$app->params['basic']['sitename']) ? Yii::$app->params['basic']['sitename'] :'' ?></title>
    <meta name="keywords" content="后台">
    <meta name="description" content="">

<!--    <link rel="stylesheet" href="/statics/themes/default-admin/css/style.css?v=4.1.0">-->

    <?php $this->registerCssFile('/statics/themes/default-admin/css/font-awesome.min.css?v=4.4.0',['depends'=>['yii\bootstrap\BootstrapAsset']]) ?>
    <?php $this->registerCssFile('/statics/themes/default-admin/css/style.css?v=4.1.0', ['depends'=>['yii\bootstrap\BootstrapAsset']]) ?>

<body>
<?php $this->beginBody() ?>
<?= $content ?>
<!-- 全局js -->
<?php $this->registerJSFile('/statics/themes/default-admin/plugins/metisMenu/jquery.metisMenu.js',['depends'=>['yii\web\JqueryAsset']]) ?>
<?php $this->registerJSFile("/statics/themes/default-admin/plugins/slimscroll/jquery.slimscroll.min.js",['depends'=>['yii\web\JqueryAsset']]) ?>
<?php $this->registerJSFile('/statics/themes/default-admin/js/hplus.js?v=4.1.0',['depends'=>['yii\web\JqueryAsset']]) ?>
<?php $this->registerJSFile('/statics/themes/default-admin/js/contabs.js',['depends'=>['yii\web\JqueryAsset']]) ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script src="/statics/themes/default-admin/plugins/layer/layer.min.js"></script>

<script>
  // 获取全局Layer
  function getCommonLayer(){
    return layer;
  }
</script>
