<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="play-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'vod_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'group_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回', ['play-group/index', 'vod_id' => $model->vod_id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
