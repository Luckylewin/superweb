<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\Func;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Major Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .today td:nth-child(3){background: #23c6c8!important;color: white}
    .tomorrow td:nth-child(3){background: skyblue!important;color: white}

</style>
<div class="major-event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Major Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'rowOptions' => function ($model, $key, $index, $grid){

            $today_start = strtotime(date('Y-m-d'));
            $today_end = $today_start + 86400;
            $tomorrow_end = $today_end + 86400;

            if ($model->base_time >= $today_start && $model->base_time < $today_end) {
                return ['class' => 'today'];
            } else if ($model->base_time >= $today_end && $model->base_time < $tomorrow_end) {
                return ['class' => 'tomorrow'];
            }
        },
        'columns' => [
            [
                "class" => "yii\grid\CheckboxColumn",
                "name" => "id",
            ],
            ['class' => 'yii\grid\SerialColumn'],



            //'id',
            [

                'attribute' => 'base_time',
                'format' => 'datetime',
                'options' => [
                        'style' => 'width:130px;'
                ]
            ],

            [
                    'attribute' => 'title',
                    'options' => [
                        'style' => 'width:130px;'
                    ]
            ],


            [
                'attribute' => 'live_match',
                'format' => 'raw',
                'value' => function ($model) {
                     $data = json_decode($model->live_match);
                     if (isset($data->teams)) {
                         $teamObject = $data->teams;
                         if (!empty($data->teams) && isset($data->teams[0]) && isset($data->teams[1])) {
                             $teams = $data->teams[0]->team_zh_name . ' ' . Html::img(Func::getAccessUrl($teamObject[0]->team_icon), ['width'=>30]);
                             $teams .= ' - ';
                             $teams .= Html::img(Func::getAccessUrl($teamObject[1]->team_icon), ['width'=>30]) . ' '. $data->teams[1]->team_zh_name;
                         } else {
                             $teams = "比赛队伍待定";
                         }

                         return $text =  $data->event_info . ' ' . $teams ;
                     }
                     return null;
                },
                'options' => ['style' => 'width:320px;']


            ],
            [
                    'attribute' => 'match_data',
                    'format' => 'raw',
                    'options' => ['style' => 'width:330px;'],
                    'value' => function($model) {
                        $data = json_decode($model->match_data);

                        $text = '';
                        if (is_array($data)) {
                            foreach ($data as $channel) {
                                $text .= Html::a($channel->channel_name, null, [
                                        'class' => 'btn btn-default btn-xs',
                                        'style'=>"margin-bottom:5px;width:100px;"
                                    ]) . '&nbsp';

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

<?php
  echo Html::button("批量删除",[
      'class' => 'gridview btn btn-danger',
  ]);
  $confirmText = Yii::t('backend', 'Are you sure?');

  $batchDelete = \yii\helpers\Url::to(['major-event/batch-delete', 'ids' => '']);
  $js =<<<JS
    $(document).on('click', '.gridview', function () {
    if (confirm('{$confirmText}')) {
      var keys = $("#grid").yiiGridView("getSelectedRows");
      window.location.href = '{$batchDelete}' + keys;
    }
})
JS;

$this->registerJs($js);

?>
