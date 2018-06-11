<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Mac */

$this->title = 'Create Mac';
$this->params['breadcrumbs'][] = ['label' => 'Macs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mac-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
