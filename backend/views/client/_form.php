<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SysClient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-client-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-4">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => '必填']) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'client_age')->textInput() ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'client_email')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'client_engname')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'client_qq')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'client_address')->textInput(['maxlength' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['client/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>





    <?php ActiveForm::end(); ?>

</div>
