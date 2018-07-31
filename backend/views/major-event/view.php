<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MajorEvent */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Major Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-event-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'time:datetime',
            'title',
            'live_match',
            'base_time:datetime',
            'match_data',
            'sort',
        ],
    ]) ?>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </p>

</div>
