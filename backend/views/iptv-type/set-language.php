<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\IptvType */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'set language';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCSSFile('statics/plugins/select2/css/select2.min.css');
$this->registerJsFile('statics/plugins/select2/js/select2.min.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="iptv-type-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="col-md-12">
        <label for="">Supported Languages</label>
        <?= $form->field($model, 'supported_language')->dropDownList(\common\models\Type::getLangCode(), [
            "multiple" => "multiple",
            'class' => 'js-example-basic-multiple',
            'style' => 'width:800px;'
        ])->label(false) ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend', 'Go Back'), \yii\helpers\Url::to(['iptv-type/index', 'list_id' => Yii::$app->request->get('id')]), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php

$js =<<<JS
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
JS;

$this->registerJs($js);

?>
