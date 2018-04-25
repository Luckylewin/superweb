<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'order_sign',
            'user.username',
            //'order_total',
             'order_money',
            'order_addtime:datetime',
            'order_paytime:datetime',
            'order_confirmtime:datetime',
            'order_info:ntext',
            [
                   'attribute' => 'order_paytype',
                   'filter' => \common\models\Order::$payType,
                    'value' => function($model) {
                        return $model->payType;
                    }
            ],

            [
                    'attribute' => 'order_ispay',
                    'filter' => \common\models\Order::$payStatus,
                    'value' => function($model) {
                        return $model->payStatus;
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{view} {delete}',
                    'options' => ['style' => 'width:200px;']
            ],
        ],
    ]); ?>
</div>
