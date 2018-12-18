<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SubClass */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sub-class-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'main_class_id',
            'name',
            'zh_name',
            'sort',
            'use_flag',
            'keyword',
            'created_at',
        ],
    ]) ?>
</div>
