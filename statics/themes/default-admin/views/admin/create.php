<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = \Yii::t('backend','Create Admin');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('backend','Admin Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-create">

    <?=$this->render('_tab_menu');?>

    <div style="margin-top: 30px;">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
