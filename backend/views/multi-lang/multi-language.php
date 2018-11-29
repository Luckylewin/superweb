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
use yii\helpers\Html;
use common\models\Type;


$langCodes = Type::getLangCode();
?>

<?= Html::hiddenInput('fid', $fid); ?>
<form action="<?= Yii::$app->request->url ?>" method="post">
<input type="hidden" name="_csrf-backend" value="<?= Yii::$app->request->csrfToken; ?>">
<?php foreach ($data as $lang => $value): ?>
<div class="col-md-12">
        <label for="<?= $lang ?>"><?= $langCodes[$lang]."({$lang})" ?></label>
        <?php if(isset($value['id'])): ?>
        <?= Html::hiddenInput("id[$lang]", $value['id']); ?>
        <?php endif; ?>
        <?= Html::input('text', "{$name}[$lang]", $value['value'], [
            'class'         => 'form-control',
            'required'      => true,
            'autocomplete'  => false,
            'id'            => $lang,
            'style'         => 'margin-bottom: 10px;'
        ]) ?>
</div>
<?php endforeach; ?>
<br/>

<div class="col-md-12" style="margin-top: 30px;">
    <?= Html::submitInput(Yii::t('backend', 'Modify'), [
        'class' => 'btn btn-success col-md-12',
        'id'    => 'submit'
    ]) ?>
</div>

</form>
