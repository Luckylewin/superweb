<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/9/27
 * Time: 10:37
 */
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;

/**@var $schemeOptions array */
/**@var $model \backend\models\form\BindVodSchemeForm */
?>


<div>
    <?php $form = ActiveForm::begin([
        'id' => 'form'
    ]); ?>

    <?= $form->field($model, 'vod_id')->hiddenInput()->label(false)?>
    <?= $form->field($model, 'scheme_id')->checkboxList($schemeOptions, [
        ]); ?>
    <?= Html::submitButton(Yii::t('backend', 'Save'), [
        'class' => 'btn btn-info'
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>

<?php

$js=<<<JS
$('#form').on('beforeSubmit', function(e) {
   var form = $(this);
   $.ajax({
        url: form.attr('action'),
        type:'post',
        data: form.serialize(),
        success: function (data) {
            if (data.status == true) {
                $('#setting-scheme-modal').modal('hide')
                layer.msg('设置成功');
            } else{
                layer.msg('设置失败');
            }
        }
   });
   return false;
})
JS;
$this->registerJs($js);

?>