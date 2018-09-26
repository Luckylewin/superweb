<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\query\ApkDetailQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apk版本列表';
$this->params['breadcrumbs'][] = ['label' => 'Apk列表', 'url' => \yii\helpers\Url::to(['apk-list/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-detail-index">



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            //'apk_ID',
            //'type',
            [
                    'attribute' => 'ver',
                    'format' => 'raw',
                    'value' => function($model) {
                        if ($model->is_newest) {
                            return '<b>' . $model->ver . '</b>';
                        }
                        return $model->ver;
                    }
            ],
            'md5',
            [
                    'attribute' => 'url',
                     'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->url, $model->getTrueUrl());
                    }
            ],
            'content:ntext',
            [
                    'attribute' => 'save_position',
                    'headerOptions' => ['class' => 'col-md-1'],
            ],
            [
                    'attribute' => 'force_update',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'format' => 'raw',
                    'value' => function($model) {
                        $theme = $model->force_update ? 'info' : 'default';
                        return Html::label($model->force_update ? "是":"否",null,[
                                'class' => 'label label-' . $theme
                        ]);
                    }
                ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'header' => Yii::t('backend', 'Operate'),
                    'headerOptions' => ['class' => 'col-md-3']
            ],
        ],
    ]); ?>

</div>

<p>
    <?= Html::a(Yii::t('backend', 'Create'), \yii\helpers\Url::to(['apk-detail/create','id' => $id]), ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['apk-list/index' ,'id' => $id]), ['class' => 'btn btn-default']) ?>
</p>
