<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\query\ApkListQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apk-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'typeName') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'class') ?>

    <?= $form->field($model, 'img') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'scheme_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
