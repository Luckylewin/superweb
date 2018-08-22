<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use common\models\OttLink;
/* @var $this yii\web\View */
/* @var $model common\models\OttLink */
/* @var $form yii\widgets\ActiveForm */
/* @var $schemes array */


?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(); ?>

        <?php if($model->isNewRecord): ?>
        <?= $form->field($model, 'channel_id')->hiddenInput()->label(false); ?>
        <?php endif; ?>


        <div class="col-md-4">
            <?= $form->field($model, 'source')->textInput(['value' => 'default']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'sort')->textInput() ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'script_deal')->dropDownList(OttLink::getSwitchStatus(), ['value'=>0]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'definition')->textInput(['value' => 0]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'method')->textInput() ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'decode')->dropDownList(OttLink::getDecodeStatus()) ?>
        </div>

        <div class="col-md-12">

            <?= $form->field($model, 'scheme_id')->checkboxList($schemes); ?>
            <?= $form->field($model, 'link')->textInput(['rows' => 6]) ?>
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

