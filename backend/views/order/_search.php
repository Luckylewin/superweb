<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'order_sign') ?>

    <?= $form->field($model, 'order_status') ?>

    <?= $form->field($model, 'order_uid') ?>

    <?= $form->field($model, 'order_total') ?>

    <?php // echo $form->field($model, 'order_money') ?>

    <?php // echo $form->field($model, 'order_ispay') ?>

    <?php // echo $form->field($model, 'order_addtime') ?>

    <?php // echo $form->field($model, 'order_paytime') ?>

    <?php // echo $form->field($model, 'order_confirmtime') ?>

    <?php // echo $form->field($model, 'order_info') ?>

    <?php // echo $form->field($model, 'order_paytype') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
