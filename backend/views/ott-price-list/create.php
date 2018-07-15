<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OttPriceList */

$this->title = 'Create Ott Price List';
$this->params['breadcrumbs'][] = ['label' => 'Ott Price Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-price-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
