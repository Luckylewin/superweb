<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SysClient */

$this->title = 'Create Sys Client';
$this->params['breadcrumbs'][] = ['label' => 'Sys Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
