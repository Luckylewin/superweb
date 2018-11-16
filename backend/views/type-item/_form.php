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


    <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'zh_name')->textInput(['autocomplete' => 'off']) ?>

    <?php if ($model->isNewRecord == false): ?>
    <div class="well">
        <?= $form->field($model, 'type_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'is_show')->radioList(['1' => '显示','0' => '隐藏']) ?>
    </div>
    <?php endif; ?>

    <?= $form->field($model, 'sort')->textInput(['autocomplete' => 'off']) ?>


    <div class="form-group">
        <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回', \common\components\Func::getLastPage(), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


