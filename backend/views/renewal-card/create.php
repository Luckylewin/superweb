<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\RenewalCard */

$this->title = 'Create Renewal Card';
$this->params['breadcrumbs'][] = ['label' => 'Renewal Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renewal-card-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
