<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = Yii::t('backend', 'Edit Menu');
$this->params['breadcrumbs'][] = Yii::t('backend', 'System Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-update">


    <?= $this->render('_form', [
        'model' => $model,
        'treeArr' => $treeArr,
    ]) ?>

</div>
