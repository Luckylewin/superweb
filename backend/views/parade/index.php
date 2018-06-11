<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ParadeQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '节目预告';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parade-index">


    <?php Pjax::begin(); ?>
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

            [
                    'label' => '关联频道',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $channel = $model->channel;

                        if ($channel) {
                            return Html::a($channel->name, \yii\helpers\Url::to(['ott-channel/view', 'id' => $channel->id]),['class'=>'btn btn-link']);
                        }

                        return Html::a('绑定频道', null, [
                                'class' => 'btn btn-default btn-sm bind',
                                'data-toggle' => 'modal',
                                'data-target' => '#bind-modal',
                                'data-id' => $model->channel_name,
                        ]);
                    }
            ],

            'upload_date',

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
                            return Html::a($title, \yii\helpers\Url::to(['parade/batch-delete','name'=>$model->channel_name]),[
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


<?php

Modal::begin([
    'id' => 'bind-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">预告(<span id="channel_name"></span>)关联频道</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

$requestUrl = Url::to(['parade/bind']);

$requestJs=<<<JS
     $(document).on('click', '.bind', function() {
                var id = $(this).attr('data-id');
                $('#channel_name').text(id);
                $.get('{$requestUrl}', {'id':id},
                    function (data) {
                        $('.modal-body').css('min-height', '70px').html(data);
                    }
           )
     });
JS;

$this->registerJs($requestJs);

Modal::end();
?>