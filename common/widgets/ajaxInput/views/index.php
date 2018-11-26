<?php



/* @var $value */
/* @var $options */
/* @var $error */
/* @var $success */

\common\widgets\ajaxInput\AjaxInputAsset::register($this);
?>

<?= \yii\helpers\Html::input('input', null, $value, $options); ?>