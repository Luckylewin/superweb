<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/26
 * Time: 10:24
 */
/* @var $model \backend\models\form\RenameForm */
?>

<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
<?= $form->field($model, 'oldName')->input('text', ['class' => 'form-control', 'readonly' => true]); ?>
<?= $form->field($model, 'name'); ?>
<?= \yii\helpers\Html::submitButton(Yii::t('backend', 'Submit'), ['class' => 'btn btn-info']); ?>
<?php \yii\widgets\ActiveForm::end(); ?>


