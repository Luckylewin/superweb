<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\SubClass */

$this->title = '批量导入';
$this->params['breadcrumbs'][] = ['label' => '返回', 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-class-create">
    <div class="sub-class-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'text')->textarea([
            'rows' =>22
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
