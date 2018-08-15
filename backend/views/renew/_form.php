<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\RechargeCard */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="recharge-card-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'card_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_secret')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_contracttime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_valid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_del')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_use')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'batch')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
