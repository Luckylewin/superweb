<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OttEvent */

$this->title = 'Create Ott Event';
$this->params['breadcrumbs'][] = ['label' => 'Ott Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-event-create">

<div class="col-md-12">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
