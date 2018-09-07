<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/9/7
 * Time: 15:42
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Recharge Cards';
$this->params['breadcrumbs'][] = $this->title;
/**@var $model \backend\models\form\LockerSwitchForm */
?>

<div class="recharge-card-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <div>
        <?php $form = ActiveForm::begin() ?>
            <?= $form->field($model, 'app_name')->textInput(['readonly' => true]); ?>
            <?= $form->field($model, 'mac')->textarea([
                    'rows' => 12
            ]); ?>
            <?= $form->field($model, 'switch')->inline(true)->dropDownList(['on' => '显示', 'off' => '隐藏']); ?>
            <?= Html::submitButton('Save', [
                'class' => 'btn btn-success'
            ]) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
