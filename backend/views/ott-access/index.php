<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel \backend\models\search\OttAccessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Accesses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-access-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ott Access', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'attribute' => 'mac',
                'headerOptions' => [
                    'class' => 'col-md-1'
                ]
            ],
            [
                    'attribute' => 'genre',
                    'headerOptions' => [
                            'class' => 'col-md-1'
                    ]
            ],
            [
                'attribute' => 'is_valid',
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-1'
                ],
                'value' => function($model) {
                    $text = $model->is_valid ? Yii::t('backend', 'Yes') : Yii::t('backend', 'Yes');
                    $class = $model->is_valid ? ['class' => 'label label-success'] : ['class' => 'label label-default'];

                    return Html::tag('span', $text, $class);
                }
            ],
            'access_key',
            [
                    'attribute' => 'deny_msg',
                    'headerOptions' => [
                        'class' => 'col-md-1'
                    ]
            ],
            [
                    'label' => Yii::t('backend', 'Order number'),
                    'value' => function($model) {
                        return $model->order['order_num'];
                    }
            ],
            'expire_time:datetime',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
