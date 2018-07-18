<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '直播收费价目列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-price-list-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ott Price List', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'value',
                'value' => function($model) {
                    return $model->getText();
                }
            ],
            'price',

            //'value',
            //,

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>

<p>
    <?= Html::a('直播收费设定', ['config/ott-setting'], ['class' => 'btn btn-primary']) ?>
</p>
