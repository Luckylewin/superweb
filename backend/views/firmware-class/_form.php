<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \backend\models\FirmwareClass;
/* @var $this yii\web\View */
/* @var $model backend\models\FirmwareClass */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="firmware-class-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_use')->dropDownList([
            '可用',
            '不可用'
    ]) ?>


    <?php if($model->isNewRecord): ?>
    <?= $form->field($model, 'order_id')->dropDownList($dropDownList, [
            'disabled' => $model->isNewRecord ? false : true
    ]) ?>
    <?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : \Yii::t('backend','Save'), ['class' => 'btn btn-success col-md-12']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
