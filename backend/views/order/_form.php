<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_sign')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_status')->textInput() ?>

    <?= $form->field($model, 'order_uid')->textInput() ?>

    <?= $form->field($model, 'order_total')->textInput() ?>

    <?= $form->field($model, 'order_money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_ispay')->textInput() ?>

    <?= $form->field($model, 'order_addtime')->textInput() ?>

    <?= $form->field($model, 'order_paytime')->textInput() ?>

    <?= $form->field($model, 'order_confirmtime')->textInput() ?>

    <?= $form->field($model, 'order_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'order_paytype')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
