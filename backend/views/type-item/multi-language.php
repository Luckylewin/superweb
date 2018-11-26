<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/26
 * Time: 16:34
 */
/* @var $languages */
/* @var $fid */
/* @var $name  */

$langCodes = \common\models\Type::getLangCode();
?>


<?= \yii\helpers\Html::hiddenInput('fid', $fid); ?>
<?php \yii\bootstrap\ActiveForm::begin(); ?>
<?php foreach ($data as $lang => $value): ?>
    <div class="col-md-5 col-md-offset-1">
        <label for="<?= $lang ?>"><?= $langCodes[$lang]."({$lang})" ?></label>
        <?= \yii\helpers\Html::input('text', "{$name}[$lang]", $value, [
            'class' => 'form-control',
            'required' => true,
            'autocomplete' => false,
        ]) ?>
    </div>
<?php endforeach; ?>
<br/>
<br/>
<hr>
<br/>
<div class="col-md-10 col-md-offset-1 text-right">
    <?= \yii\helpers\Html::submitButton(Yii::t('backend', 'Submit'), [
        'class' => 'btn btn-success'
    ]) ?>
</div>

<?php \yii\bootstrap\ActiveForm::end(); ?>