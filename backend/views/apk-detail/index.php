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


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            //'apk_ID',
            //'type',
            'ver',
            'md5',
            [
                    'attribute' => 'url',
                     'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->url, $model->getTrueUrl());
                    }
            ],
            'content:ntext',
            'sort',
            [
                    'attribute' => 'force_update',
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
                    'header' => '操作',
                    'options' => [
                            'style' => 'width:200px;'
                    ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<p>
    <?= Html::a('新增版本', \yii\helpers\Url::to(['apk-detail/create','apk_ID' => $id]), ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['apk-list/index']), ['class' => 'btn btn-default']) ?>
</p>
