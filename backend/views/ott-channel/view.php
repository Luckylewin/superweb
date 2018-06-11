<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '频道列表', 'url' => \yii\helpers\Url::to(['ott-channel/index', 'sub-id' => $model->subClass->id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-channel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('返回', \yii\helpers\Url::to(['ott-channel/index','sub-id'=>$model->subClass->id]), ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sub_class_id',
            'name',
            'zh_name',
            'keywords',
            'sort',
            'use_flag',
            'channel_number',
            'image',
            'alias_name',
        ],
    ]) ?>

</div>
