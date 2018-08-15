<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IptvUrlResolution */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="iptv-url-resolution-form">
    <?php $form = ActiveForm::begin([
            'method' => 'post'
    ]); ?>

    <table  class="table table-bordered detail-view">
        <tr>
            <th width="15%">名称</th>
            <td>
                <div class="col-md-3">
                    <?= $form->field($model, 'method')->label(false); ?>
                </div>
            </td>
        </tr>

        <input type="hidden" name="id" value="">
        <tr>
            <th width="15%">Referer</th>
            <td >
                <div class="col-md-3">
                    <?= $form->field($model, 'referer')->label(false); ?>
                </div>
            </td>
        </tr>

        <tr>
            <th width="15%">Url</th>
            <td >
                <div class="col-md-3">
                    <?= $form->field($model, 'url')->label(false); ?>
                </div>
            </td>
        </tr>

        <tr>
            <th width="15%">过期时间</th>
            <td >
                <div class="col-md-3">
                    <?= $form->field($model, 'expire_time')->label(false); ?>
                </div>
            </td>
        </tr>


        <tr>
            <th width="15%">c正则表达式</th>
            <td height="150px;">
                <div class="col-md-3">
                    <?= $form->field($model, 'c_i[]')->textInput(['value' => $model->c_i[0]])->label('一级正则表达式(a)'); ?>
                    <?= $form->field($model, 'c_i[]')->textInput(['value' => $model->c_i[1]])->label('一级正则表达式(b)'); ?>
                    <?= $form->field($model, 'c_i[]')->textInput(['value' => $model->c_i[2]])->label('一级正则表达式(c)'); ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'c_ii[]')->textInput(['value' => $model->c_ii[0]])->label('二级正则表达式(a)'); ?>
                    <?= $form->field($model, 'c_ii[]')->textInput(['value' => $model->c_ii[1]])->label('二级正则表达式(b)'); ?>
                    <?= $form->field($model, 'c_ii[]')->textInput(['value' => $model->c_ii[2]])->label('二级正则表达式(c)'); ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'c_iii[]')->textInput(['value' => $model->c_iii[0]])->label('三级正则表达式(a)'); ?>
                    <?= $form->field($model, 'c_iii[]')->textInput(['value' => $model->c_iii[1]])->label('三级正则表达式(b)'); ?>
                    <?= $form->field($model, 'c_iii[]')->textInput(['value' => $model->c_iii[2]])->label('三级正则表达式(c)'); ?>
                </div>
            </td>
        </tr>

        <tr>
            <th width="15%">android正则表达式</th>
            <td height="150px;" >
                <div class="col-md-3">
                    <?= $form->field($model, 'android_i[]')->textInput(['value' => $model->android_i[0]])->label('一级正则表达式(a)'); ?>
                    <?= $form->field($model, 'android_i[]')->textInput(['value' => $model->android_i[1]])->label('一级正则表达式(b)'); ?>
                    <?= $form->field($model, 'android_i[]')->textInput(['value' => $model->android_i[2]])->label('一级正则表达式(c)'); ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'android_ii[]')->textInput(['value' => $model->android_ii[0]])->label('二级正则表达式(a)'); ?>
                    <?= $form->field($model, 'android_ii[]')->textInput(['value' => $model->android_ii[1]])->label('二级正则表达式(b)'); ?>
                    <?= $form->field($model, 'android_ii[]')->textInput(['value' => $model->android_ii[2]])->label('二级正则表达式(c)'); ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'android_iii[]')->textInput(['value' => $model->android_iii[0]])->label('三级正则表达式(a)'); ?>
                    <?= $form->field($model, 'android_iii[]')->textInput(['value' => $model->android_iii[1]])->label('三级正则表达式(b)'); ?>
                    <?= $form->field($model, 'android_iii[]')->textInput(['value' => $model->android_iii[2]])->label('三级正则表达式(c)'); ?>
                </div>

            </td>
        </tr>
    </table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '保存编辑', ['class' => $model->isNewRecord? 'btn btn-success' : 'btn btn-info']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['resolve/index']),['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
