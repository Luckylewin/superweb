<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SysClient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-client-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput(['readonly' => true]); ?>
        <?= $form->field($model, 'admin_id')->dropDownList($admin) ?>
        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
