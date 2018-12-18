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

    <?= \common\widgets\frameButton::widget([
        'url' =>  \yii\helpers\Url::to(['firmware-class/create']),
        'content' => Yii::t('backend', 'Create'),
        'options' => ['class' => 'btn btn-success btn-sm'],
        'icon' => 'fa-edit'
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            [
                    'attribute' => 'is_use',
                    'value' => function($model) {
                        $status = [Yii::t('backend', 'Available'), Yii::t('backend', 'Unavailable')];
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
                    'label' => '文件数量',
                    'value' => function($model){
                        return count($model->detail);
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{firmware-list} {update} {delete}',
                    'buttons' => [
                            'firmware-list' => function($url, $model) {
                                return \common\widgets\frameButton::widget([
                                    'url' =>  \yii\helpers\Url::to(['firmware-detail/index', 'firmware_id' => $model->id]),
                                    'content' => Yii::t('backend', 'File List'),
                                    'options' => ['class' => 'btn btn-success btn-sm'],
                                    'icon' => 'fa-file'
                                ]);
                            }
                    ],
                    'visibleButtons' => [
                        'delete' => Yii::$app->user->can('firmware-class/delete')
                ]
            ],
        ],
    ]); ?>
</div>
