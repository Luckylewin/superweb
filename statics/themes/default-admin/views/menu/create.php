<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = Yii::t('backend', 'Create Menu');
$this->params['breadcrumbs'][] = Yii::t('backend', 'Sytem Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?=$this->render('_tab_menu');?>

    <?= $this->render('_form', [
        'model' => $model,
        'treeArr' => $treeArr,
    ]) ?>

</div>
