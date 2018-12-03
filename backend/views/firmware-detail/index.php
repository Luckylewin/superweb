<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Firmware Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmware-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Firmware Detail', ['create', 'firmware_id' => $firmware_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                    'label' => '名称',
                    'value' => 'firmware.name'
            ],
            'ver',
            'md5',
            [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->url, \common\components\Func::getAccessUrl($model->url), [
                                'class' => 'btn btn-link fa fa-link'
                        ]);
                    }
            ],

            //'content:ntext',
            //'sort',
            //'force_update',
            //'type',
            //'is_use',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{update} {delete}',
                    /*'visibleButtons' => [
                        'delete' => \Yii::$app->user->can('firmware-detail/delete'),
                    ]*/
            ],
        ],
    ]); ?>
</div>
