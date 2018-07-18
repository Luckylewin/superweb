<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '直播收费模式设定';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-price-list-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'data')->dropDownList($mode)->label($this->title) ?>

    <div class="form-group">
        <?= Html::submitButton('设定', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('返回', ['ott-price-list/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

