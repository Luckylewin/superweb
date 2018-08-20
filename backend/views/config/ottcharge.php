<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\form\OttSettingForm  */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Live charging mode setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-price-list-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mode')->dropDownList($model->mode_select, [
            'id' => 'mode'
    ]); ?>

    <?= $form->field($model, 'free_day')->textInput([
            'style' => $model->mode == 0 ? "display:none" : '',
            'id' => 'free_day'
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'set'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['ott-price-list/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS
    $('#mode').change(function() {
     
        var val = $(this).val();
        if (val != 0) {
         $('#free_day').parent().show();   
        } else {
         $('#free_day').parent().hide();   
        }
    });
JS;

$js = $this->registerJs($js); ?>

