<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/22
 * Time: 10:09
 */

/* @var $id */
/* @var $idField */
/* @var $field */
/* @var $url */
/* @var $method */
/* @var $isField */
/* @var $success */
/* @var $error */
/* @var $checked */
/* @var $csrf */

\common\widgets\switchInput\SwitchInputAsset::register($this);
?>

<input class='switch-component'
       type='checkbox'
       data-id="<?= $id ?>"
       data-field="<?= $field ?>"
       data-link="<?= $url ?>"
       data-type="<?= $method ?>"
       data-idField="<?= $idField ?>"
       data-success="<?= $success ?>"
       data-error="<?= $error ?>"
       data-csrf="<?= $csrf ?>"
       <?php if($checked): ?> checked="checked"<?php endif; ?>
>

