<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\Func;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Event Teams';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th{text-align: center;}
    td{vertical-align: middle!important;text-align: center}
</style>
<div class="ott-event-team-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'event_id',
            'team_name',
            'team_zh_name',
            //'team_introduce',
            [
                'attribute' => 'team_icon',
                'format' => ['image', ['width' => '40']],
                'options' => ['style' => 'border-radius:10px;overflow:hidden'],
                'value' => function($model) {
                    return Func::getAccessUrl($model->team_icon);
                }
            ],
            //'team_icon_big',
            'team_country',
            //'team_info',
            //'team_alias_name',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>

    <p>
        <?= Html::a('添加队伍', Url::to(['ott-event-team/create', 'event_id' => Yii::$app->request->get('event_id')]), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), Url::to(['ott-event/index']), ['class' => 'btn btn-default']) ?>
    </p>
</div>
