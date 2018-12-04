<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

$this->title = $model->name;
?>
<div class="ott-channel-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
