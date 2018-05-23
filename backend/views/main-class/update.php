<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MainClass */


$this->params['breadcrumbs'][] = ['label' => '分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="main-class-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
