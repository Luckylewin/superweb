<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\MainClass;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\OttAccess */
/* @var $form yii\widgets\ActiveForm */

$genres = ArrayHelper::map(MainClass::find()->all(), 'list_name', 'name');
?>

<div class="ott-access-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mac')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'genre')->dropDownList($genres) ?>

    <?= $form->field($model, 'is_valid')->dropDownList([
            0 => Yii::t('backend', 'No'),
            1 => Yii::t('backend', 'Yes')
    ]) ?>

    <?= $form->field($model, 'deny_msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend', 'Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
