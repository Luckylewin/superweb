<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MacSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '终端状态管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mac-index">

    <h1><?php Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
                'class' => 'common\widgets\goPager',
                'go' => true
        ],
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],

        'columns' => [
            [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => [
                        'style' => 'width:40px;'
                    ],
            ],


            [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'MAC',
                    'options' => [
                        'style' => 'width:30px;'
                    ],
            ],

            [
                    'attribute' => 'MAC',
                    'options' => [
                        'style' => 'width:140px;'
                    ]
            ],

            [
                'attribute' => 'SN',
                'options' => [
                    'style' => 'width:140px;'
                ]
            ],



            [
                'attribute' => 'regtime',
                'value' => function($data) {
                    return date('Y-m-d', strtotime($data->regtime));
                },
                'options' => [
                    'style' => 'width:100px;'
                ]
            ],

            [
                'attribute' => 'duetime',
                'value' => function($data) {
                    if ($data->duetime == '0000-00-00 00:00:00') {
                        return "无限期";
                    }
                    return date('Y-m-d', strtotime($data->duetime));
                },
                'options' => [
                    'style' => 'width:100px;'
                ]
            ],

            [
                'attribute' => 'contract_time',
                'value' => function($data){
                    return str_replace([' year', ' month', ' day'],['年', '个月', '天'], $data->contract_time);
                },
                'options' => [
                    'style' => 'width:70px;'
                ]
            ],

            [
                 'attribute' => 'use_flag',
                 'format' => 'raw',
                 'filter' => $searchModel::getUseFlagList(),
                 'value' => function($data) use($searchModel) {
                       return $searchModel->getUseFlagWithLabel($data->use_flag);
                 },
                 'options' => [
                         'style' => 'width:56px;'
                 ]
             ],

            [
                'attribute' => 'online_status',
                'format' => 'raw',
                'label' => '在线状态',
                'filter' => $searchModel::getUseFlagList(),
                'value' => function() use($searchModel) {
                    return $searchModel->getOnlineWithLabel();
                },
                'options' => [
                    'style' => 'width:46px;'
                ]
            ],


            [
                'attribute' => 'ver',
                'options' => [
                    'style' => 'width:50px;'
                ]
            ],


            [
                'attribute' => 'logintime',
                'value' => function($data) {
                    if ($data->logintime == '0000-00-00 00:00:00') {
                        return "";
                    }
                    return date('Y-m-d H:i', strtotime($data->logintime));
                },
                'options' => [
                    'style' => 'width:130px;'
                ]
            ],

            //',
            //'type',
            //'duetime',
            //',

            [
                'class' => 'common\grid\MyActionColumn',
                'header' => '操作',
                'template' => '{renew}&nbsp{view}&nbsp;{update}&nbsp;{delete}',
                'buttons' => [
                        'renew' => function($url, $model, $key) {
                            $title = "续费";
                            $options = [
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                                'class' => 'btn btn-default btn-xs'
                            ];

                            return Html::a($title, \yii\helpers\Url::to(['renew/renew','mac'=>$model->MAC]), $options);
                        }
                ]
              
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

    <div>
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>

        <?= Html::button('批量删除', [
                'class' => 'btn btn-danger gridview',
                'url' => \yii\helpers\Url::to(['mac/batch-delete'])
        ]) ?>

        <?= Html::a('批量新增', ['batch-create'], ['class' => 'btn btn-default']) ?>

        <?= Html::a('重构缓存', ['batch-cache'], ['class' => 'btn btn-default']) ?>

        <?= Html::a('导出mac-sn', \yii\helpers\Url::to(['mac/export', 'queryParams' => $queryParams]), ['class' => 'btn btn-default']) ?>

    </div>
    </div>


<?php

$this->registerJs("
        $(document).on('click', '.gridview', function () {
                var keys = $('#grid').yiiGridView('getSelectedRows');
                var data = {macs:keys};
                var url = $(this).attr('url');
                var _csrf_token = $('#_csrf_token');
                   
                $.post(url,data,function(d){
                    if (d.status === 0) {
                        window.location.reload();
                    }    
                });
});
    ");

?>