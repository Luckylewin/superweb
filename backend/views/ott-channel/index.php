<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OttChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '频道列表';
$this->params['breadcrumbs'][] = ['label' => $mainClass->zh_name? $mainClass->zh_name:$mainClass->name, 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = ['label' => $subClass->zh_name?$subClass->zh_name:$subClass->name, 'url' => Url::to(['sub-class/index', 'main-id' => $mainClass->id])];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .modal-lg {
        /* respsonsive width */
        width: 95%;
    }
</style>

<div class="ott-channel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加频道', ['create','sub-id' => $subClass->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('重新排列频道号', ['sub-class/reset-number','main_class_id' => $mainClass->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
                'class' => 'table table-bordered table-hover',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'image',
            //'id',
            //'sub_class_id',
            'name',
            'zh_name',
            'keywords',
            'sort',
            [
                    'attribute' => 'use_flag',
                    'value' => function($model) {
                        return $model->use_flag ? '可用' : '不可用';
                    }
            ],
            'channel_number',
            //'alias_name',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{channel} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;{view} {update} {delete}',
                    'buttons' => [
                            'channel' => function($url, $model, $key) {
                                return Html::a("&nbsp;>>&nbsp;", null, [
                                        'class' => 'btn btn-success btn-xs load-link',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#links-modal',
                                        'data-id' => $model->id,
                                ]);
                            }
                    ],
                    'options' => [
                            'style' => 'width:250px;'
                    ]
            ],
        ],
    ]); ?>

</div>

<?php

Modal::begin([
         'id' => 'links-modal',
          'size' => Modal::SIZE_LARGE,
          'header' => '<h4 class="modal-title">链接</h4>',
          'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

$requestUrl = Url::to(['ott-link/index']);
$switchUrl = Url::to(['ott-link/update', 'field'=>'use_flag']);

$requestJs=<<<JS
    $('.load-link').click(function(){
        $.getJSON('{$requestUrl}', {channel_id:$(this).attr('data-id')}, function(data) {
            var table = '<table class="table table-bordered"><thead><tr><th>方案</th><th>来源</th><th width="120px">链接</th><th>算法</th><th width="50px">解码</th><th width="60px">清晰度</th><th width="70px">状态</th><th style="width:150px;">操作</th></tr></thead><tbody>';
            var tr = '';
            
            $.each(data,function(){
                console.log($(this));
                    tr += '<tr link-id="' +  $(this).attr('id')  + '">';
                    tr += '<td>' + $(this).attr('schemeText') + '</td>';
                    tr += '<td>' + $(this).attr('source') + '</td>';
                    tr += '<td>' + $(this).attr('link') + '</td>';
                    tr += '<td>' + $(this).attr('method') + '</td>';
                    tr += '<td>' + $(this).attr('decode') + '</td>';
                    tr += '<td>' + $(this).attr('definition') + '</td>';
                    tr += '<td class="use-flag">' + ($(this).attr('use_flag_text')) + '</td>';
                    tr += '<td><button class="btn btn-info btn-xs disabled">脚本开关</button>&nbsp;<button class="btn btn-primary btn-xs use-switch">可用开关</btn></td></tr>';
            });
                
            table += tr;
            table += '</tbody></table>';
            $('.modal-body').html(table)
            
        })
    })
    
    $('body').on('click', '.use-switch' ,function(){
        var tr = $(this).parent().parent();
        var link_id = tr.attr('link-id');
        
        $.getJSON('{$switchUrl}', {id:link_id}, function(back) {
             $(tr).find('.use-flag').html(back.msg);
        });
    })
    
JS;

$this->registerJs($requestJs);



Modal::end()

?>




