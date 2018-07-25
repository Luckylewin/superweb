<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Crontab;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '定时任务';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .status{margin: 0 auto;width: 20px;height: 20px;border-radius: 20px;}
    .switch_on, .status_normal {background: #396;}
    .switch_off{background: #c0c0c0;}
    .status_ready{background-color: #a6e1ec}
    .status_running{background-color: #23c6c8}
    .status_error{background-color: #aa0000}
</style>

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
            [
                    'attribute' => 'switch',
                    'format' => 'raw',
                    'label' => '任务开关',
                    'value' => function ($model) {
                        if ($model->switch == Crontab::SWITCH_ON) {
                            return '<div class="switch_on status"></div>';
                        } else {
                            return '<div class="switch_off status"></div>';
                        }
                    }
            ],

            [
                'attribute' => 'status',
                'format' => 'raw',
                'label' => '任务状态',
                'value' => function ($model) {
                    if ($model->switch == Crontab::SWITCH_OFF) {
                        return "<div class='status switch_off'></div>";
                    }
                    $map = [Crontab::NORMAL => 'status_normal',Crontab::READY => 'status_ready', Crontab::RUNNING => 'status_ready', Crontab::ERROR => 'status_error'];
                    return "<div class='status {$map[$model->status]}'></div>";
                }
            ],

            [
                    'attribute' => 'last_rundate',
                    'value' => function($model) {
                        return Yii::$app->formatter->asRelativeTime(strtotime($model->last_rundate));
                    }
            ],
            [
                    'attribute' => 'next_rundate',
                    'value' => function($model) {
                        return Yii::$app->formatter->asRelativeTime(strtotime($model->next_rundate));
                    }
            ],
            //'execmemory',
            'exectime',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{update} {delete}',
                    'size' => 'btn-sm'
            ],

        ],

    ]); ?>


</div>
