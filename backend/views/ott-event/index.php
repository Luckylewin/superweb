<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\Func;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    td{vertical-align: middle!important;}
</style>
<div class="ott-event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ott Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'event_name',
            'event_name_zh',
            //'event_introduce',
            [
                    'attribute' => 'event_icon',
                    'format' => ['image',['width'=>100]],
                    'value' => function($model) {
                        return Func::getAccessUrl($model->event_icon);
                    }
            ],
            //'event_icon_big',
            //'sort',
            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{create-team} {update} {delete}',
                    'size' => 'btn-sm',
                    'buttons' => [
                            'create-team' => function($url, $model) {
                                return Html::a('队伍列表', Url::to(['ott-event-team/index', 'event_id' => $model->id]), [
                                        'class' => 'btn btn-sm btn-info'
                                ]);
                            }
                    ]
            ],
        ],
    ]); ?>
</div>
