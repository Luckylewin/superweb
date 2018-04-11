<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '定时任务';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crontab-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建定时任务', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'route',
            'crontab_str',
            'switchText',
            'statusText',
            'last_rundate',
            'next_rundate',
            'execmemory',
            'exectime',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
