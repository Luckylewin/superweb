<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Firmware Classes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmware-class-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Firmware Class', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            [
                    'attribute' => 'is_use',
                    'value' => function($model) {
                        $status = ['可用', '不可用'];
                        return $status[$model->is_use];
                    }
            ],

            [
                    'attribute' => 'order_id',
                    'value' => function($model) {
                        return $model->order ? $model->order->order_num : null;
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{firmware-list} {update} {delete}',
                    'buttons' => [
                            'firmware-list' => function($url, $model) {

                                return Html::a('文件列表',['firmware-detail/index', 'firmware_id' => $model->id], [
                                        'class' => 'btn btn-info btn-sm'
                                ]);
                            }
                    ]
            ],
        ],
    ]); ?>
</div>
