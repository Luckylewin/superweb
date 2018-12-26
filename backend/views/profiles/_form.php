<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VodProfiles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vod-profiles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'screen_writer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'director')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'actor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_tag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plot')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imdb_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tmdb_id')->textInput() ?>

    <?= $form->field($model, 'tmdb_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'douban_id')->textInput() ?>

    <?= $form->field($model, 'douban_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'douban_voters')->textInput() ?>

    <?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'banner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fill_status')->textInput() ?>

    <?= $form->field($model, 'douban_search')->textInput() ?>

    <?= $form->field($model, 'imdb_search')->textInput() ?>

    <?= $form->field($model, 'tmdb_search')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
