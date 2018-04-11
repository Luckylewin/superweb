<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\query\AlbumNameKaraokeQuery */
/* @var $form yii\widgets\ActiveForm */
?>



    <div class="album-name-karaoke-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-md-1">
            <?= $form->field($model, 'ID') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'area')->dropDownList(\app\models\AlbumNameKaraoke::getLang())->label("语言种类") ?>
        </div>

        <div class="col-md-3">
            <?=  $form->field($model, 'tags') ?>
        </div>



        <?php // echo $form->field($model, 'directors') ?>



        <?php // echo $form->field($model, 'info') ?>

        <?php // echo $form->field($model, 'area') ?>

        <?php // echo $form->field($model, 'keywords') ?>

        <?php // echo $form->field($model, 'wflag') ?>

        <?php // echo $form->field($model, 'year') ?>

        <?php // echo $form->field($model, 'mod_version') ?>

        <?php // echo $form->field($model, 'updatetime') ?>

        <?php // echo $form->field($model, 'totalDuration') ?>

        <?php // echo $form->field($model, 'flag') ?>

        <?php // echo $form->field($model, 'hit_count') ?>

        <?php // echo $form->field($model, 'voole_id') ?>

        <?php // echo $form->field($model, 'price') ?>

        <?php // echo $form->field($model, 'is_finish') ?>

        <?php // echo $form->field($model, 'yesterday_viewed') ?>

        <?php // echo $form->field($model, 'utime') ?>

        <?php // echo $form->field($model, 'url') ?>

        <?php // echo $form->field($model, 'act_img') ?>

        <?php // echo $form->field($model, 'download_flag') ?>

        <div class="col-md-2">
            <label for="submit"></label>
            <div class="form-group" id="submit">
                <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


