<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Mac */
/* @var $model backend\models\Mac */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \backend\models\Mac;
use backend\assets\MyAppAsset;
use backend\models\SysClient;
use yii\helpers\Url;

MyAppAsset::register($this);

$this->title = 'Create Mac';
$this->params['breadcrumbs'][] = ['label' => 'Macs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="mac-create">
    <div class="mac-form">
        <?php $form = ActiveForm::begin([
            'id' => 'form-save',
            'enableAjaxValidation' => true,
            'validationUrl' => Url::toRoute(['mac/validate-form','scenario' => 'create']),
        ]); ?>

        <?= $form->field($model, 'MAC')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

        <?php $form->field($model, 'use_flag')->dropDownList(Mac::getUseFlagList()) ?>

        <?= $form->field($model, 'client_name')->dropDownList(SysClient::getAll(), ['prompt' => Yii::t('backend', 'Not specified')]); ?>

        <?= $form->field($model, 'is_hide')->dropDownList(Mac::getIsHiDEList()) ?>

        <?= $form->field($model, 'contract_time',[
            'options' => [
                'style' => 'width:170px',
            ],
            'template' => '{label}<div class="input-group">
            {input}
            <div class="input-group-btn" style="width: 70px;">
            '.
                $form->field($model, 'unit')->dropDownList(Mac::getContractList())
                .'   
            </div>
        </div>{hint}{error}'
        ])->textInput()?>

        <div class="form-group">
            <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success submit']) ?>
            <?= Html::a(Yii::t('backend', 'Go Back'), ['mac/index'], ['class' => 'btn btn-default']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

<?php
$requestUrl = Url::to(['mac/create']);

$js =<<<JS
    $(document).on('beforeSubmit', 'form#form-save', function () { 
    var form = $(this); 
    //返回错误的表单信息 
    if (form.find('.has-error').length) 
    { 
        return false; 
    } 
    //表单提交 
    $.ajax({ 
      url  : form.attr('action'), 
      type  : 'post', 
      data  : form.serialize(), 
      success: function (response){ 
        if(response.status == 0){ 
            window.location.reload(); 
        } 
      }, 
      error : function (){ 
        alert('系统错误'); 
        return false; 
      } 
    }); 
    return false; 
  }); 
JS;

$this->registerJs($js);

?>