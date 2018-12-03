<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pid')->dropDownList([0 => Yii::t('backend', 'Top Route')]+$treeArr, ['encode' => false]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput()->hint('格式: index/index&id=2&type=1 或 http://开头') ?>

    <?= $form->field($model, 'icon_style')->textInput(['maxlength' => true])->hint('格式为: 图标样式, 例如: fa-star') ?>

    <?= $form->field($model, 'type')->dropDownList(['rule' => '仅权限', 'all' => '权限+菜单']); ?>

    <?php if($model->type == 'rule'): ?>
        <div class="menu-field" style="display: none">
    <?php else: ?>
        <div class="menu-field" >
    <?php endif; ?>
        <?= $form->field($model, 'display')->dropDownList($model->getDisplays()); ?>
    </div>


    <?= $form->field($model, 'sort')->textInput()->hint('数值越小排序越前') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?php if (strpos(urldecode(Yii::$app->request->getReferrer()), 'menu/index') !== false): ?>
            <?= Html::a('返回', ['menu/index'], ['class' => 'btn btn-default']) ?>
        <?php else: ?>
            <?= Html::a('返回', ['menu/routes'], ['class' => 'btn btn-default']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$JS=<<<JS
    $('#menu-type').change(function() {
        var val = $(this).val();
        if (val === 'all') {
            $('.menu-field').show();
        } else {
            $('.menu-field').hide();
        }
    })
JS;

$this->registerJs($JS);

?>
