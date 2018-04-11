<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RechargeCard */

$this->title = 'Create Recharge Card';
$this->params['breadcrumbs'][] = ['label' => 'Recharge Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recharge-card-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
