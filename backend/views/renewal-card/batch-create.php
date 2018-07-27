<?php
use yii\widgets\ActiveForm;
use backend\blocks\RenewCardBlock;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/27
 * Time: 16:53
 */
/* @var $model \backend\models\form\BatchCreateCardForm */
?>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(RenewCardBlock::$typeSelect) ?>
    <?= $form->field($model, 'num'); ?>
    <?= Html::submitButton('生成', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('返回', ['index'], ['class' => 'btn btn-default']) ?>
<?php ActiveForm::end(); ?>

