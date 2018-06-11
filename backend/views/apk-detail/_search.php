<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\query\ApkDetailQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apk-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'apk_ID') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'ver') ?>

    <?= $form->field($model, 'md5') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'force_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
