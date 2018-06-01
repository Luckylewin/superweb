<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \backend\models\Mac;
use backend\assets\MyAppAsset;
use backend\models\SysClient;
use yii\helpers\Url;

MyAppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\Mac */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="mac-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-id',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute(['mac/validate-form']),
    ]); ?>

    <?= $form->field($model, 'MAC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'use_flag')->dropDownList(Mac::getUseFlagList()) ?>

    <?= $form->field($model, 'client_name')->dropDownList(SysClient::getAll(), ['prompt' => '暂不指定']); ?>

    <?= $form->field($model, 'contract_time',[
            'options' => [
                     'style' => 'width:170px'
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
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


