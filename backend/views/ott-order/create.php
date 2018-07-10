<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OttOrder */

$this->title = 'Create Ott Order';
$this->params['breadcrumbs'][] = ['label' => 'Ott Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
