<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\OttLinkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ott-link-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'channel_id') ?>

    <?= $form->field($model, 'link') ?>

    <?= $form->field($model, 'source') ?>

    <?= $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'use_flag') ?>

    <?php // echo $form->field($model, 'format') ?>

    <?php // echo $form->field($model, 'script_deal') ?>

    <?php // echo $form->field($model, 'definition') ?>

    <?php // echo $form->field($model, 'method') ?>

    <?php // echo $form->field($model, 'decode') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
