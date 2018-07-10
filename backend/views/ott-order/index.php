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
                    'genre',
                    'order_num',
                    'mainOrder.order_money',
                    'mainOrder.order_addtime:datetime',
                    [
                        'attribute' => 'mainOrder.order_ispay',
                        'value' => function($model) {
                            $payText = ['未支付', '已支付'];
                            return $payText[$model->mainOrder->order_ispay];
                        }
                    ],
                    'mainOrder.order_info',
                    [
                            'attribute' => 'expire_time',
                            'format' => 'datetime',
                            'filter' => false
                    ],
                    //'is_valid',

                    [
                        'class' => 'common\grid\MyActionColumn',
                        'template' => '{delete}'
                    ],
            ],
        ]);
    }
    catch(\Exception $e) {
        echo $e->getMessage();
    }

    ?>
</div>
