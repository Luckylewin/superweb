<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MainClass */

$this->title = '创建分类';
$this->params['breadcrumbs'][] = ['label' => '分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-class-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
