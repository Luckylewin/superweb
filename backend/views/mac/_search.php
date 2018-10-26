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
            <h3 class="panel-title"><?= Yii::t('backend', 'Query panel') ?></h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get'
            ]); ?>
            <div class="col-md-3">
                <?= $form->field($model, 'MAC')->textInput([
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'SN')->textInput([
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'use_flag')->dropDownList(MacSearch::getUseFlagList(),[
                        'prompt' => Yii::t('backend', 'please choose')
                ]) ?>
            </div>

            <div class="col-md-3">
                <?php $client = SysClient::find()->asArray()->all(); ?>
                <?php $client = is_null($client) ? [] : ArrayHelper::map($client, 'id', 'name'); ?>
                <?= $form->field($model, 'client_name')->dropDownList($client, [
                    'prompt' => Yii::t('backend', 'please choose')
                ]); ?>
            </div>

            <div class="col-md-3">

                <?= $form->field($model, 'is_online')->dropDownList([
                    '1' => Yii::t('backend', 'Online'),
                    '0' => Yii::t('backend', 'Offline')

                ], [
                    'prompt' => Yii::t('backend', 'please choose')
                ]); ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'regtime')->textInput([
                    'class' => 'range form-control',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'logintime')->textInput([
                    'class' => 'range form-control',
                    'autocomplete' => 'off'
                ])?>
            </div>





        </div>
    </div>


    <?php // echo $form->field($model, 'contract_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['mac/index'],['class' => 'btn btn-default']) ?>
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
            ,range: false
            ,theme: 'grid'
        });
    });

</script>
<?php \common\widgets\Jsblock::end() ?>
