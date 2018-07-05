<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\VodSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vod-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php $form->field($model, 'vod_id') ?>

    <?php $form->field($model, 'vod_cid') ?>

    <?php $form->field($model, 'vod_name') ?>

    <?php $form->field($model, 'vod_ename') ?>

    <?php $form->field($model, 'vod_title') ?>

    <?php // echo $form->field($model, 'vod_keywords') ?>

    <?php // echo $form->field($model, 'vod_type') ?>

    <?php // echo $form->field($model, 'vod_actor') ?>

    <?php // echo $form->field($model, 'vod_director') ?>

    <?php // echo $form->field($model, 'vod_content') ?>

    <?php // echo $form->field($model, 'vod_pic') ?>

    <?php // echo $form->field($model, 'vod_pic_bg') ?>

    <?php // echo $form->field($model, 'vod_pic_slide') ?>

    <?php // echo $form->field($model, 'vod_area') ?>

    <?php // echo $form->field($model, 'vod_language') ?>

    <?php // echo $form->field($model, 'vod_year') ?>

    <?php // echo $form->field($model, 'vod_continu') ?>

    <?php // echo $form->field($model, 'vod_total') ?>

    <?php // echo $form->field($model, 'vod_isend') ?>

    <?php // echo $form->field($model, 'vod_addtime') ?>

    <?php // echo $form->field($model, 'vod_filmtime') ?>

    <?php // echo $form->field($model, 'vod_hits') ?>

    <?php // echo $form->field($model, 'vod_hits_day') ?>

    <?php // echo $form->field($model, 'vod_hits_week') ?>

    <?php // echo $form->field($model, 'vod_hits_month') ?>

    <?php // echo $form->field($model, 'vod_stars') ?>

    <?php // echo $form->field($model, 'vod_status') ?>

    <?php // echo $form->field($model, 'vod_up') ?>

    <?php // echo $form->field($model, 'vod_down') ?>

    <?php // echo $form->field($model, 'vod_ispay') ?>

    <?php // echo $form->field($model, 'vod_price') ?>

    <?php // echo $form->field($model, 'vod_trysee') ?>

    <?php // echo $form->field($model, 'vod_play') ?>

    <?php // echo $form->field($model, 'vod_server') ?>

    <?php // echo $form->field($model, 'vod_url') ?>

    <?php // echo $form->field($model, 'vod_inputer') ?>

    <?php // echo $form->field($model, 'vod_reurl') ?>

    <?php // echo $form->field($model, 'vod_jumpurl') ?>

    <?php // echo $form->field($model, 'vod_letter') ?>

    <?php // echo $form->field($model, 'vod_skin') ?>

    <?php // echo $form->field($model, 'vod_gold') ?>

    <?php // echo $form->field($model, 'vod_golder') ?>

    <?php // echo $form->field($model, 'vod_length') ?>

    <?php // echo $form->field($model, 'vod_weekday') ?>

    <?php // echo $form->field($model, 'vod_series') ?>

    <?php // echo $form->field($model, 'vod_copyright') ?>

    <?php // echo $form->field($model, 'vod_state') ?>

    <?php // echo $form->field($model, 'vod_version') ?>

    <?php // echo $form->field($model, 'vod_douban_id') ?>

    <?php // echo $form->field($model, 'vod_douban_score') ?>

    <?php // echo $form->field($model, 'vod_scenario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
