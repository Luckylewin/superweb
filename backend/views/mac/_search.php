<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\query\MacQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mac-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'MAC') ?>

    <?= $form->field($model, 'SN') ?>

    <?= $form->field($model, 'use_flag') ?>

    <?= $form->field($model, 'ver') ?>

    <?= $form->field($model, 'regtime') ?>

    <?php // echo $form->field($model, 'logintime') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'duetime') ?>

    <?php // echo $form->field($model, 'contract_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
