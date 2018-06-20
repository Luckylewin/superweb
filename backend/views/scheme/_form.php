<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Scheme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scheme-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'schemeName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cpu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ddr')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回', ['scheme/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
