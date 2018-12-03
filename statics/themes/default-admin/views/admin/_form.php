<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'email')->textInput(['autocomplete' => 'off']) ?>

    <?php if ($model->isNewRecord == false): ?>
        <?= $form->field($model, 'status')->radioList($model->getStatusTexts()) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', ['admin/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
