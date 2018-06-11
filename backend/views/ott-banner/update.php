<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OttBanner */

$this->title = 'Update Ott Banner: ';
$this->params['breadcrumbs'][] = ['label' => 'Ott Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-banner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
