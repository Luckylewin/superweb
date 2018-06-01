<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\SysClient;
use \backend\models\Search\MacSearch;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MacSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']) ?>

<div class="mac-search">


    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">查询面板</h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get'
            ]); ?>
            <div class="col-md-3">
                <?= $form->field($model, 'MAC') ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'SN') ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'use_flag')->dropDownList(MacSearch::getUseFlagList(),[
                        'prompt' => '请选择'
                ]) ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'ver') ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'regtime')->textInput([
                    'class' => 'range form-control'
                ])->label('注册时间(范围)') ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'logintime')->textInput([
                    'class' => 'range form-control'
                ])->label('登录时间(范围)') ?>
            </div>

            <div class="col-md-3">
                <?php $client = SysClient::find()->asArray()->all(); ?>
                <?php $client = is_null($client) ? [] : ArrayHelper::map($client, 'id', 'name'); ?>
                <?= $form->field($model, 'client_name')->dropDownList($client, [
                        'prompt' => '请选择'
                ]); ?>
            </div>

        </div>
    </div>


    <?php // echo $form->field($model, 'contract_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php \common\widgets\Jsblock::begin() ?>
<script>
    lay('.range').each(function(){
        laydate.render({
            elem: this
            ,trigger: 'click'
            ,type: 'date'
            ,range: true
            ,theme: 'grid'
        });
    });
</script>
<?php \common\widgets\Jsblock::end() ?>
