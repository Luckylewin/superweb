<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\web\AssetBundle as AppAsset;
use yii\widgets\Breadcrumbs;


AppAsset::register($this);


$this->registerJsFile('/statics/themes/default-admin/plugins/toastr/toastr.min.js', ['depends'=>['yii\web\JqueryAsset']]);
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
    <link href="/statics/themes/default-admin/css/style.css?v=4.1.0" rel="stylesheet">
    <link href="/statics/themes/default-admin/plugins/bootstrap-v3.3/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/statics/themes/default-admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/statics/themes/default-admin/plugins/toastr/toastr.min.css" rel="stylesheet">

<body>
<?php $this->beginBody() ?>
<div class="wrapper">
    <div class="panel">
        <div class="panel-body">
            <div class="col-md-12">
                <!-- 面包屑导航 -->
                <div>
                    <?=    Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'homeLink' => false
                    ]); ?>
                </div>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": true,
        "progressBar": true,
        "positionClass": "toast-top-full-width",
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "slideUp"
    }
</script>

<?= \common\widgets\Toastr::widget();?>