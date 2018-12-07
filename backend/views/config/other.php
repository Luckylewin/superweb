<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model \backend\models\form\config\OtherForm */

?>
<style>
    #myForm input{ border-radius: 4px;}
    .control-label { height: 34px;line-height: 4px;}
    .form-group{margin-bottom: 4px!important;}
    .setting-fields{ background: #5bc0de;color: #ffffff;border:none;}
</style>
<div class="config-form">
    <?= $this->render('_tab_menu');?>

    <div style="margin-top: 50px;">
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => '<div class="form-group">
                                       <label class="col-xs-4 control-label">{label} </label>
                                       <div class="col-lg-6 input-group pull-left">{input}</div>
                                       <div class="col-sm-4 col-xs-10">
                                            {error}
                                       </div>               
                                      </div>',
                'inputOptions' => ['class' => 'form-control'],
            ],
            'id' => 'myForm'
        ]); ?>

        <div class="col-md-12">

               <div class="col-md-2">
                   <div class="well setting-fields">
                       阿里云OSS 设置
                   </div>
               </div>
               <div class="col-md-10">
                  <div class="well">
                      <?= $form->field($model, 'oss_access_id')->textInput(); ?>
                      <?= $form->field($model, 'oss_access_key')->textInput(); ?>
                      <?= $form->field($model, 'oss_endpoint')->textInput(); ?>
                      <?= $form->field($model, 'oss_bucket')->textInput(); ?>
                  </div>
               </div>

        </div>


        <div class="col-md-12">
            <div class="col-md-2">
                <div class="well setting-fields">
                    Nginx 设置
                </div>
            </div>
            <div class="col-md-10">
                <div class="well">
                    <?= $form->field($model, 'nginx_port')->textInput(); ?>
                    <?= $form->field($model, 'nginx_secret')->textInput(); ?>
                    <?= $form->field($model, 'nginx_dir')->textInput(); ?>
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="col-md-2">
                <div class="well setting-fields">
                    百度翻译 设置
                </div>
            </div>
            <div class="col-md-10">
                <div class="well">
                    <?= $form->field($model, 'baidu_translate_id')->textInput(); ?>
                    <?= $form->field($model, 'baidu_translate_key')->textInput(); ?>
                </div>
            </div>
        </div>

        <div class="md-col-12">
            <div class="form-group">
                <div class="col-md-2 col-md-offset-5 text-center">
                    <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-info col-md-12']) ?>
                </div>
            </div>
        </div>


        <?php ActiveForm::end(); ?>
    </div>

</div>