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

    <?php if($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off','placeholder' => '密码至少为6位']) ?>
    <?php else: ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off','placeholder' => '不修改密码请留空']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'email')->textInput(['autocomplete' => 'off']) ?>

    <?php if ($model->isNewRecord == false): ?>
        <?= $form->field($model, 'status')->radioList($model->getStatusTexts()) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary col-md-12']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
