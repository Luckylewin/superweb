<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IptvTypeItem */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="iptv-type-item-form">

    <?php $form = ActiveForm::begin([
            'id' => 'form-save'
    ]); ?>

    <?= $form->field($model, 'type_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'zh_name')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'sort')->textInput(['autocomplete' => 'off']) ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


