<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MacSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Terminal status management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mac-index">

    <h1><?php Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php Pjax::begin(); ?>

    <?php

    try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => 'common\widgets\goPager',
                'firstPageLabel' => Yii::t('backend', 'First Page'),
                'lastPageLabel' => Yii::t('backend', 'Last Page'),
                'go' => true
            ],
            "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],

            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => [
                        'style' => 'width:30px;'
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
                    'attribute' => 'is_online',
                    'format' => 'raw',
                    'label' => Yii::t('backend', 'Online Status'),
                    'value' => function($model) {
                        return $model->getOnlineWithLabel($model->is_online);
                    },
                    'options' => [ 'style' => 'width:56px;']
                ],

                'MAC',
                'SN',



                [
                    'attribute' => 'logintime',
                    'value' => function($data) {
                        if ($data->logintime == '0000-00-00 00:00:00') {
                            return Yii::t('backend', 'no record');
                        }
                        return Yii::$app->formatter->asRelativeTime(strtotime($data->logintime));
                    },
                    'options' => [
                        'style' => 'width:130px;'
                    ]
                ],

                [
                    'attribute' => 'regtime',
                    'value' => function($data) {
                        if ($data->regtime == '0000-00-00 00:00:00') {
                            return "-";
                        }
                        return date('Y-m-d', strtotime($data->regtime));
                    },
                    'options' => [
                        'style' => 'width:90px;'
                    ]
                ],

                [
                    'attribute' => 'duetime',
                    'value' => function($data) {
                        if ($data->use_flag == 1) {
                            return Yii::t('backend', 'Unused');
                        }

                        if ($data->duetime == '0000-00-00 00:00:00' && empty($data->contract_time)) {
                            return  Yii::t('backend', 'Indefinite');
                        } else if($data->duetime == '0000-00-00 00:00:00') {
                            return '-';
                        } else {
                            return date('Y-m-d', strtotime($data->duetime));
                        }


                    },
                    'options' => [
                        'style' => 'width:100px;'
                    ]
                ],

                [
                    'attribute' => 'use_flag',
                    'format' => 'raw',
                    'value' => function($data) use($searchModel) {
                        return $searchModel->getUseFlagWithLabel($data->use_flag);
                    },
                    'options' => ['style' => 'width:76px;']
                ],





                //',
                //'type',
                //'duetime',
                //',

                [
                    'class' => 'common\grid\MyActionColumn',
                    'header' => Yii::t('backend', 'Operation'),
                    'template' => '{renew}&nbsp{view}&nbsp;{update}&nbsp;{delete}',
                    'buttons' => [
                        'renew' => function($url, $model, $key) {
                            $title = Yii::t('backend', 'Renewal');
                            $options = [
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                                'class' => 'btn btn-default btn-xs'
                            ];

                            return Html::a($title, \yii\helpers\Url::to(['renew/renew','mac'=>$model->MAC]), $options);
                        }
                    ],


                ],
            ],
        ]);
    }catch (\Exception $e) {

    }

     ?>
    <?php Pjax::end(); ?>

    <div>
        <?= Html::a(Yii::t('backend', 'Create'), null, [
                'class' => 'btn btn-success create',
                'data-toggle' => 'modal',
                'data-target' => '#create-modal',
        ]) ?>

        <?= Html::button(Yii::t('backend', 'Batch Deletion'), [
                'class' => 'btn btn-danger gridview',
                'url' => \yii\helpers\Url::to(['mac/batch-delete'])
        ]) ?>

        <?= Html::a(Yii::t('backend', 'Sync online-state'), \yii\helpers\Url::to(['mac/sync-online']), ['class' => 'btn btn-default']) ?>

        <?= Html::a(Yii::t('backend', 'Batch Creation'), ['batch-create'], ['class' => 'btn btn-default']) ?>

        <?= Html::a(Yii::t('backend', 'Export Mac-Sn'), \yii\helpers\Url::to(['mac/export', 'queryParams' => $queryParams]), ['class' => 'btn btn-default']) ?>

        <?= Html::a(Yii::t('backend', 'Import Mac-Sn'), \yii\helpers\Url::to(['mac/import']), ['class' => 'btn btn-default']) ?>


    </div>
    </div>


<?php



Modal::begin([
    'id' => 'create-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">新增MAC地址</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

$requestUrl = Url::to(['mac/create']);

$requestJs=<<<JS
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
     $(document).on('click', '.create', function() {
               
                $.get('{$requestUrl}', {},
                    function (data) {
                        $('.modal-body').css('min-height', '200px').html(data);
                    }
                )
            })
JS;

$this->registerJs($requestJs);

Modal::end();


?>