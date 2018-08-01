<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \backend\models\FirmwareClass;
/* @var $this yii\web\View */
/* @var $model backend\models\FirmwareClass */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firmware-class-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_use')->dropDownList([
            '可用',
            '不可用'
    ]) ?>

    <?= $form->field($model, 'order_id')->dropDownList(FirmwareClass::getDropDownList(), [
            'disabled' => $model->isNewRecord ? false : true
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : \Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['firmware-class/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
