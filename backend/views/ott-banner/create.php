<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OttBanner */

$this->title = 'Create Ott Banner';
$this->params['breadcrumbs'][] = ['label' => 'Ott Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-banner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
