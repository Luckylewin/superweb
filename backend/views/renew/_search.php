<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\query\RechargeCardQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recharge-card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'card_num') ?>

    <?= $form->field($model, 'card_secret') ?>

    <?= $form->field($model, 'card_contracttime') ?>

    <?= $form->field($model, 'is_valid') ?>

    <?= $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'is_use') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'batch') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
