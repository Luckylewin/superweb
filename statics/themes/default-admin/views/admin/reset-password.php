<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model \backend\models\form\ResetPasswordForm */


$this->title = '密码修改';
$this->params['breadcrumbs'][] = ['label' => '管理员密码修改', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_confirm')->passwordInput() ?>
        <?= Html::submitButton('修改', ['class' => 'btn-info btn']) ?>
    <?php ActiveForm::end(); ?>
</div>
