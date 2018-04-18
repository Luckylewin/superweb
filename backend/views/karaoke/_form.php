<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Karaoke */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="album-name-karaoke-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_del')->dropDownList(\backend\models\Karaoke::$delStatus) ?>

    <?= $form->field($model, 'albumName')->textInput(['rows' => 6]) ?>

    <?php $form->field($model, 'albumImage')->textInput(['rows' => 6]) ?>

    <?= $form->field($model, 'mainActor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'area')->dropDownList(\backend\models\Karaoke::getLang()) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'hit_count')->textInput()->label('热度') ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>




    <?php if($model->isNewRecord == false): ?>
    <?= $form->field($model, 'hit_count')->textInput(['maxlength' => true])->label('热度') ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
