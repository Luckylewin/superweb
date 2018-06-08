<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Major Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Major Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'time:datetime',
            'title',
            'live_match',
            'base_time:datetime',
            'match_data',
             'sort',
            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>
</div>
