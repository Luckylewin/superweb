<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SubClass */

$this->title = 'Create Sub Class';
$this->params['breadcrumbs'][] = ['label' => 'Sub Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-class-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
