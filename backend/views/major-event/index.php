<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\Func;

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
            'time:date',
            'title',

            [
                    'attribute' => 'base_time',
                    'format' => ['date','php:H:i']
            ],

            [
                'attribute' => 'live_match',
                'format' => 'raw',
                'value' => function ($model) {
                     $data = json_decode($model->live_match);
                     $teamObject = $data->teams;
                     $teams = $data->teams[0]->team_zh_name . ' ' . Html::img(Func::getAccessUrl($teamObject[0]->team_icon), ['width'=>30]);
                     $teams .= ' - ';
                     $teams .= Html::img(Func::getAccessUrl($teamObject[1]->team_icon), ['width'=>30]) . ' '. $data->teams[1]->team_zh_name;
                     return $text =  $data->event_info . ' ' . $teams ;

                }
            ],
            [
                    'attribute' => 'match_data',
                    'format' => 'raw',
                    'value' => function($model) {
                        $data = json_decode($model->match_data);

                        $text = '';
                        if (is_array($data)) {
                            foreach ($data as $channel) {
                                $text .= Html::tag('span', $channel->channel_name, ['class' => 'label label-default']) . ' ';
                            }
                        }
                        return $text;
                     }

            ],


            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => ' {update} {delete}'
            ],
        ],
    ]); ?>
</div>
