<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Jsblock;
use common\widgets\OssUploader;
use backend\models\Scheme;

/* @var $this yii\web\View */
/* @var $model backend\models\ApkList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apk-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'typeName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>

    <?= OssUploader::widget([
            'model' => $model,
            'form' => $form,
            'field' => 'img',
            'dir' => $model->dir,
            'bed' => true,
            'allowExtension' => [
                'Image files' => 'jpg,jpeg,png,gif'
            ]
    ]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <table class="table table-bordered" style="font-size: 13px; width: 100%;">

        <tbody>
        <tr>
            <td>
                <?= $form->field($model, "scheme_id")->checkboxList(\yii\helpers\ArrayHelper::map(Scheme::getAll(),'id', 'schemeName')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <a class="btn btn-default btn-xs check"  href="#" style="margin-right: 10px">全选</a>
                <a class="btn btn-default btn-xs check"  href="#" style="margin-right: 10px">取消</a>
            </td>
        </tr>
        </tbody>

    </table>
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']); ?>
        <?= Html::a('返回',Yii::$app->request->referrer, ['class' => 'btn btn-default']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>



<?php JsBlock::begin(); ?>
<script>
    $('.check').click(function(){
       var flag = !$(this).index();
       $('input[type=checkbox]').prop('checked', flag);
       return false;
    });
</script>
<?php JsBlock::end(); ?>

