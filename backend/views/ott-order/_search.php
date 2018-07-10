<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OttOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ott-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'oid') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'genre') ?>

    <?= $form->field($model, 'order_num') ?>

    <?= $form->field($model, 'expire_time') ?>

    <?php // echo $form->field($model, 'is_valid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
