<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vodlink */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vodlink-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'video_id')->dropDownList([$vod->vod_id => $vod->vod_name]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'season')->textInput(); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'episode')->textInput() ?>
    </div>

    <div class="col-md-12">


        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'hd_url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'plot')->textarea(['rows'=>6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
