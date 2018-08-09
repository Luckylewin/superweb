<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

$subClass = $model->subClass;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Channel List'), 'url' => \yii\helpers\Url::to(['ott-channel/index', 'sub-id' => is_null($subClass) ? '' :  $model->subClass->id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-channel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>


        <?php if ($subClass): ?>
        <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['ott-channel/index','sub-id'=>$model->subClass->id]), ['class' => 'btn btn-default']) ?>
        <?php endif; ?>

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


<?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>