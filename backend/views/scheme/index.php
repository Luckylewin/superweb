<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Schemes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scheme-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Scheme', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'schemeName',
            'cpu',
            'flash',
            'ddr',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{bind-admin} {update} {delete}',
                    'buttons' => [
                            'bind-admin' => function($url, $model) {
                                 return Html::a('关联用户', \yii\helpers\Url::to(['scheme/bind-user', 'id'=>$model->id]), [
                                         'class' => 'btn btn-success'
                                 ]);
                            }
                    ]
            ],
        ],
    ]); ?>
</div>
