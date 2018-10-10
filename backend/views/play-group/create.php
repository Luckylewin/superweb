<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlayGroup */

$this->title = 'Create Play Group';
$this->params['breadcrumbs'][] = ['label' => 'Play Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
