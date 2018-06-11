<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MajorEvent */

$this->title = 'Create Major Event';
$this->params['breadcrumbs'][] = ['label' => 'Major Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-event-create">

    <div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
