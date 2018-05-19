<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

$this->title = 'Update Ott Channel:';

$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ott-channel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
