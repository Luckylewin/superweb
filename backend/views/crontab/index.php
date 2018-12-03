<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Crontab;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Timed task');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .inline {display: inline-block}
    .middle{margin: 0 auto;}
    .status{width: 20px;height: 20px;border-radius: 20px;}
    .switch_on, .status_normal {background: #396;}
    .switch_off{background: #c0c0c0;}
    .status_ready{background-color: #a6e1ec}
    .status_running{background-color: #23c6c8}
    .status_error{background-color: #aa0000}
</style>

<div class="crontab-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
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

                    'value' => function ($model) {
                        if ($model->switch == Crontab::SWITCH_ON) {
                            return '<div class="switch_on status middle"></div>';
                        } else {
                            return '<div class="switch_off status middle"></div>';
                        }
                    }
            ],

            [
                'attribute' => 'status',
                'format' => 'raw',

                'value' => function ($model) {
                    if ($model->switch == Crontab::SWITCH_OFF) {
                        return "<div class='status switch_off middle'></div>";
                    }
                    $map = [Crontab::NORMAL => 'status_normal',Crontab::READY => 'status_ready', Crontab::RUNNING => 'status_running', Crontab::ERROR => 'status_error'];
                    return "<div class='status middle {$map[$model->status]}'></div>";
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
                    'class'    => 'common\grid\MyActionColumn',
                    'template' => '{update} {delete}',
                    'size'     => 'btn-sm',
                    'visibleButtons' => [
                        'delete' => Yii::$app->user->can('crontab/delete')
                    ]
            ],
        ],

    ]); ?>

    <div>
        <div style="float: right">
            <div class="status status_normal inline"></div> <span><?= Yii::t('backend', 'Normal') ?></span>&nbsp;
            <div class="status status_ready inline"></div> <span><?= Yii::t('backend', 'Ready') ?></span>&nbsp;
            <div class="status status_running inline"></div> <span><?= Yii::t('backend', 'Running') ?></span>&nbsp;
            <div class="status status_error inline"></div> <span><?= Yii::t('backend', 'Error') ?></span>&nbsp;
        </div>
    </div>

</div>
