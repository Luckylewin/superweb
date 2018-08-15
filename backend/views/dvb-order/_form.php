<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DvbOrder */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="dvb-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_id')->dropDownList(\backend\models\SysClient::getAll()) ?>

    <div class="form-group">
        <?= Html::submitButton('新增', ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['dvb-order/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
