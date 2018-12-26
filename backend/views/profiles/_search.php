<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\VodProfilesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vod-profiles-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'alias_name') ?>

    <?= $form->field($model, 'screen_writer') ?>

    <?= $form->field($model, 'director') ?>

    <?php // echo $form->field($model, 'actor') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'language') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'tag') ?>

    <?php // echo $form->field($model, 'user_tag') ?>

    <?php // echo $form->field($model, 'plot') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'imdb_id') ?>

    <?php // echo $form->field($model, 'imdb_score') ?>

    <?php // echo $form->field($model, 'tmdb_id') ?>

    <?php // echo $form->field($model, 'tmdb_score') ?>

    <?php // echo $form->field($model, 'douban_id') ?>

    <?php // echo $form->field($model, 'douban_score') ?>

    <?php // echo $form->field($model, 'douban_voters') ?>

    <?php // echo $form->field($model, 'length') ?>

    <?php // echo $form->field($model, 'cover') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'banner') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'fill_status') ?>

    <?php // echo $form->field($model, 'douban_search') ?>

    <?php // echo $form->field($model, 'imdb_search') ?>

    <?php // echo $form->field($model, 'tmdb_search') ?>

    <?php // echo $form->field($model, 'profile_language') ?>

    <?php // echo $form->field($model, 'media_type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
