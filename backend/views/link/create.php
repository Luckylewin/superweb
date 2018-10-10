<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Vodlink */

$this->title = 'Create Vodlink';
$this->params['breadcrumbs'][] = ['label' => 'Vodlinks', 'url' => ['index','group_id' => $model->group_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vodlink-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
