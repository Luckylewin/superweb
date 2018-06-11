<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Parade */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']);
?>
<style>
    .rows{margin-bottom: 4px;}
</style>
<div class="parade-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-id',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute(['validate-form']),
    ]); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'channel_id')->hiddenInput()->label(false); ?>

        <?= $form->field($model, 'channel_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'parade_date')->textInput() ?>

        <?= $form->field($model, 'upload_date')->hiddenInput(['value' => date('Y-m-d')])->label(false) ?>

        <?= $form->field($model, 'parade_data')->hiddenInput()->label(false) ?>
    </div>

    <div class="col-md-12 form-inline">

        <div class="rows">
            <?= Html::textInput('hour[]', '00', [
                'class' => 'form-control item',
                'style' => 'width:60px;',
                'placeholder' => '时'
            ]) ?>

            <?= Html::textInput('minute[]', '00', [
                'class' => 'form-control item',
                'style' => 'width:60px;',
                'placeholder' => '分'
            ]) ?>

            <?= Html::textInput('name[]', null, [
                'class' => 'form-control item',
                'style' => 'width:600px;',
                'placeholder' => '预告内容'
            ]) ?>

            <?= Html::button('<i class="glyphicon-plus"></i>', [
                    'class' => 'btn btn-sm btn-info append item'
            ]) ?>

           </div>

    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton('新增', ['class' => 'btn btn-success', 'style' => 'margin-top:30px;']) ?>
            <?= Html::a('返回', ['parade/index'], ['class' => 'btn btn-default', 'style' => 'margin-top:30px;']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php \common\widgets\Jsblock::begin() ?>
    <script>
        laydate.render({
            elem: '#parade-parade_date'
        });

        $(document).on('click', '.append', function() {
            var node = $(this).parent();
            node = node.clone();
            var items = node.find('.item');
            $.each(items, function(){
                var previous = $(this).val();
                $(this).val('');
                if($(this).index() == '1') {
                    $(this).val('00');
                } else if($(this).index() == '0') {
                    var newVal = parseInt(previous) + 1;
                    if (newVal < 10)  {
                        newVal = '0' + newVal;
                    }
                    $(this).val(newVal);
                }
            });
            $('.form-inline').append(node);
            $(this).remove();
        });
    </script>
<?php \common\widgets\Jsblock::end() ?>
