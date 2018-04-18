<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Vod;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '点播分类列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-list-index">

    <p>
        <?= Html::a('Create Vod List', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'list_id',
            //'list_pid',
            //'list_sid',
            'list_name',
            'list_dir',
            //'list_status',
            //'list_keywords',
            //'list_title',
            //'list_description',
            [
                    'attribute' => 'list_ispay',
                    'value' => function($model) {
                        return Vod::$chargeStatus[$model->list_ispay];
                    }
            ],
            [
                    'attribute' => 'list_price',
                    'value' => function($model) {
                        return $model->list_price . ' 金币';
                    }
            ],
            [
                'attribute' => 'list_trysee',
                'value' => function($model) {
                    return $model->list_trysee . ' 分钟';
                }
            ],

            //'list_extend:ntext',

            ['class' => 'common\grid\MyActionColumn'],
        ],
    ]); ?>
</div>
