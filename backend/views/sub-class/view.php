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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

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
