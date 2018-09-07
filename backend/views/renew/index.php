<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RechargeCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recharge Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recharge-card-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Recharge Card', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'card_num',
            'card_secret',
            'card_contracttime',
            'is_valid',
            'is_del',
            //'is_use',
            //'create_time',
            //'batch',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
