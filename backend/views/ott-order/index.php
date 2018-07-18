<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OttOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php

    try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn'],
                    'uid',

                    [
                        'attribute' => 'order_num',
                        'options' => ['style'=>'width:100px;'],
                    ],
                    [
                        'attribute' => 'genre',
                        'filter' => false,
                        'options' => ['style'=>'width:60px;'],
                    ],
                    'mainOrder.order_money',
                    'mainOrder.order_addtime:datetime',
                    'mainOrder.order_paytime:datetime',

                    'mainOrder.order_info',
                    [
                            'attribute' => 'expire_time',
                            'value' => function($model) {
                                return floor($model->expire_time / (30 * 86400)) . '个月';
                            },
                            'filter' => false
                    ],

                    [
                        'attribute' => 'mainOrder.order_ispay',
                        'format' => 'raw',
                        'value' => function($model) {
                            $payText = ['未支付', '已支付'];
                            $payText = $payText[$model->mainOrder->order_ispay];

                            if ($model->mainOrder->order_ispay) {
                                return Html::button($payText,['class' => 'btn btn-sm btn-success']);
                            } else {
                                return Html::button($payText,['class' => 'btn btn-sm btn-default']);
                            }
                        }
                    ],

                    //'is_valid',
                    [
                        'class' => 'common\grid\MyActionColumn',
                        'template' => '{delete}',
                        'size' => 'btn btn-sm'
                    ],
            ],
        ]);
    }
    catch(\Exception $e) {
        echo $e->getMessage();
    }

    ?>
</div>
