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

    <p>
        <?= Html::a('Create Ott Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'uid',
            'genre',
            'order_num',
            'mainOrder.order_money',
            'mainOrder.order_addtime',
            [
                    'attribute' => ''
            ],
            'mainOrder.order_info',
            'expire_time:datetime',
            //'is_valid',

            [
                'class' => 'common\grid\MyActionColumn',
                'template' => '{delete}'
            ],
        ],
    ]); ?>
</div>
