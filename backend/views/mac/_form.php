<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mac */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mac-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'MAC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'use_flag')->textInput() ?>

    <?= $form->field($model, 'ver')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'regtime')->textInput() ?>

    <?= $form->field($model, 'logintime')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'duetime')->textInput() ?>

    <?= $form->field($model, 'contract_time')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
