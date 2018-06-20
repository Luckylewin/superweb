<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DvbOrder */

$this->title = 'Create Dvb Order';
$this->params['breadcrumbs'][] = ['label' => 'Dvb Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dvb-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
