<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\OttChannelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ott-channel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sub_class_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'zh_name') ?>

    <?= $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'use_flag') ?>

    <?php // echo $form->field($model, 'serverlize') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'alias_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
