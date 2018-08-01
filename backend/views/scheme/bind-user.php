<?php
use yii\bootstrap\Html;

/* @var $model \backend\models\form\BindSchemeForm */
/* @var $scheme \backend\models\Scheme */


?>


<?php $form = \yii\widgets\ActiveForm::begin(); ?>
    <div class="col-md-6">
        <?= $form->field($model, 'scheme_id')->dropDownList([$scheme->id => $scheme->schemeName]); ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'admin_id')->checkboxList($model->getAdmin()) ?>
    </div>

    <div class="col-md-12">
        <?= Html::submitButton(\Yii::t('backend','Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['scheme/index'], ['class' => 'btn btn-default']) ?>
    </div>

<?php \yii\widgets\ActiveForm::end(); ?>

