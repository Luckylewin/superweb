<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Parade */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parade-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'channel_id')->textInput() ?>

    <?= $form->field($model, 'channel_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parade_date')->textInput() ?>

    <?= $form->field($model, 'upload_date')->textInput() ?>

    <?= $form->field($model, 'parade_data')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('新增', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
