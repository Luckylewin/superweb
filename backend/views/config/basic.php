<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model \backend\models\form\config\BasicForm */

?>
<style>
    #myForm input{ border-radius: 4px;}
    .control-label { height: 34px;line-height: 4px;}
</style>
    <div class="config-form">
        <?= $this->render('_tab_menu');?>

        <div style="margin-top: 50px;">
            <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => '<div class="form-group">
                                       <label class="col-xs-4 control-label">{label} </label>
                                       <div class="col-lg-4 col-xs-6 input-group pull-left">{input}</div>
                                        <div class="col-sm-4 col-xs-10">
                                            {error}
                                        </div>               
                                      </div>',
                        'inputOptions' => ['class' => 'form-control'],
                    ],
                    'id' => 'myForm'
            ]); ?>

            <?= $form->field($model, 'name')->textInput(); ?>
            <?= $form->field($model, 'host')->textInput(); ?>
            <?= $form->field($model, 'email')->textInput(); ?>

            <div class="form-group">

                <div class="col-md-2 col-md-offset-5 text-center">
                    <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-info col-md-12']) ?>
                </div>
            </div>


            <?php ActiveForm::end(); ?>
        </div>

    </div>