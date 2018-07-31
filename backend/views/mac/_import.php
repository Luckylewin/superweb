<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use backend\models\Mac;
/* @var $this yii\web\View */
/* @var $model common\models\SubClass */

$this->title = '批量导入';
$this->params['breadcrumbs'][] = ['label' => '返回', 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-class-create">
    <div class="sub-class-form">

        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-12">
            <div class="well">
                <p>格式：mac,sn</p>
            </div>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'client_id')->dropDownList($clients, ['prompt' => '请选择']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'contract_time',[
                'options' => ['style' => 'width:240px'],
                'template' => '{label}<div class="input-group">{input}
            <div class="input-group-btn" style="width: 70px;">
            '.$form->field($model, 'unit')->dropDownList(Mac::getContractList()) .'</div></div>{hint}{error}'
            ])->textInput()?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'text')->textarea([
                'rows' =>15
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('backend','Go Back'), ['main-class/index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>



        <?php ActiveForm::end(); ?>

    </div>

</div>
