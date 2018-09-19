<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\OttAccessSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ott-access-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'mac') ?>

    <?= $form->field($model, 'genre') ?>

    <?= $form->field($model, 'is_valid') ?>

    <?= $form->field($model, 'deny_msg') ?>

    <?= $form->field($model, 'expire_time') ?>

    <?php // echo $form->field($model, 'access_key') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
