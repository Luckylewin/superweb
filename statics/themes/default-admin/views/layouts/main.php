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
    <?php $this->registerCssFile('/statics/plugins/page/pace-blue-theme-flash.css') ?>
    <?php $this->registerJsFile('/statics/plugins/page/page.min.js', ['depends'=>['yii\web\JqueryAsset']]); ?>

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

</script>
<?= \common\widgets\Toastr::widget();?>
</html>
<?php $this->endPage() ?>





