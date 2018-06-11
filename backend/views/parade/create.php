<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Parade */

$this->title = '添加';
$this->params['breadcrumbs'][] = ['label' => '预告列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parade-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
