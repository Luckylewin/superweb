<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\OttLink */
/* @var $form yii\widgets\ActiveForm */


?>

    <div class="col-md-12">
        <?php $form = ActiveForm::begin(); ?>

        <?php if($model->isNewRecord): ?>
        <?= $form->field($model, 'channel_id')->hiddenInput()->label(false); ?>
        <?php endif; ?>


        <div class="col-md-4">
            <?= $form->field($model, 'source')->textInput(['value' => 'default']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'sort')->textInput(['value' => 0]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'script_deal')->dropDownList(['关','开'], ['value'=>0]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'definition')->textInput(['value' => 0]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'method')->textInput(['value' => 'null']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'decode')->dropDownList(['软解', '硬解']) ?>
        </div>

        <div class="col-md-12">

            <?= $form->field($model, 'scheme_id')->checkboxList($schemes); ?>
            <?= $form->field($model, 'link')->textInput(['rows' => 6]) ?>
            <div class="form-group">
                <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

