<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \backend\models\Mac;
use backend\assets\MyAppAsset;

MyAppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Mac */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="mac-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'MAC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'use_flag')->dropDownList(Mac::getUseFlagList()) ?>

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
        </div>{hint}{error}</div>'
    ])->textInput()?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php \common\widgets\Jsblock::begin() ?>


<?php \common\widgets\Jsblock::end() ?>

