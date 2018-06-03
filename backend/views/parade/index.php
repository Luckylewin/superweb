<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\query\ParadeQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '节目预告';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parade-index">


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => 'common\widgets\goPager',
            'go' => true
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'channel_name',
                'format' => 'raw',
                'value' => function($data) {
                    $channel = $data->channel;
                    return Html::a($data->channel_name,'#',['btn btn-link']);
                }
            ],
            'upload_date',
            //'parade_data:ntext',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'buttons' => [
                            'view' => function($url, $model, $key) {
                                $title = "查看";
                                return Html::a($title, \yii\helpers\Url::to(['parade/list-channel','name'=>$model->channel_name]),[
                                        'class'=>'btn btn-info btn-sm',
                                        'title' => $title,
                                        'aria-label' => $title,
                                        'data-pjax' => '0',
                                ]);
                            },
                        'delete' => function($url, $model, $key) {
                            $title = "删除";
                            return Html::a($title, \yii\helpers\Url::to(['parade/batch-delete','id'=>$model->channel_id]),[
                                'class'=>'btn btn-danger btn-sm',
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                            ]);
                        }
                    ],
                'template' => '{view} &nbsp;{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<p>
    <?= Html::a('添加预告', ['create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a("生成缓存($version)", ['create-cache'], ['class' => 'btn btn-default']) ?>
</p>
