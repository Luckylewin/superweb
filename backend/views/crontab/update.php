<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Crontab */

$this->title = '更新定时任务';
$this->params['breadcrumbs'][] = ['label' => '定时任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="crontab-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
