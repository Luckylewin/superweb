<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Vodlink */

$this->title = 'Create Vodlink';
$this->params['breadcrumbs'][] = ['label' => 'Vodlinks', 'url' => ['index','vod_id' => $vod->vod_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vodlink-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'vod' => $vod
    ]) ?>

</div>
