<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SubClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类列表';
$this->params['breadcrumbs'][] = ['label' => $mainClass->name, 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .grid-view td{
        text-align: center;
        vertical-align:middle!important;
    }
</style>
<div class="sub-class-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('添加分类', '#', [
                'class' => 'btn btn-success',
                'data-toggle' => 'modal',
                'data-target' => '#create-modal',
                'id' => 'create'
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [ 'class' => 'table table-bordered table-hover' ],
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            [
                "class" => "yii\grid\CheckboxColumn",
                "name" => "id",
            ],

            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'contentOptions' => [
                        'class' => 'ajax-td'
                ],
                'options' => ['style' => 'width:200px;'],
                'format' => 'raw',
                'value' => function($model) {
                    $str =  Html::textInput('name', $model->name, [
                        'class' => 'form-control ajax-update',
                        'field' => 'name',
                        'data-id' => $model->id,
                        'old-value' => $model->name
                    ]);
                    return $str = "<div class='text'>{$model->name}</div>" . "<div class='input' style='display: none'>$str</div>";
                }
            ],

            [
                'attribute' => 'zh_name',
                'contentOptions' => [
                    'class' => 'ajax-td'
                ],
                'options' => ['style' => 'width:200px;'],
                'format' => 'raw',
                'value' => function($model) {
                    $str =  Html::textInput('sort', $model->zh_name, [
                        'class' => 'form-control ajax-update',
                        'field' => 'zh_name',
                        'data-id' => $model->id,
                        'old-value' => $model->zh_name
                    ]);
                    return $str = "<div class='text'>{$model->zh_name}</div>" . "<div class='input' style='display: none'>$str</div>";
                }
            ],

            [
                'attribute' => 'sort',
                'contentOptions' => [ 'class' => 'ajax-td'],
                'options' => ['style' => 'width:70px;'],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::textInput('sort', $model->sort, [
                            'class' => 'form-control ajax-update',
                            'field' => 'sort',
                            'data-id' => $model->id,
                            'old-value' => $model->sort
                    ]);
                }
            ],

            [
                'attribute' => 'use_flag',
                'contentOptions' => ['class' => 'ajax-td'],
                'format' => 'raw',
                'value' => function($model) {
                    $icon =  $model->use_flag ? '<i style="color: #23c6c8;font-size: large" class="glyphicon glyphicon-ok-circle"></i>' : '<i style="color: #953b39;font-size: large" class="glyphicon glyphicon-remove-circle"></i>';
                    $dropDownList = Html::dropDownList('use_flag', $model->use_flag, ['不可用','可用'] , [
                        'class' => 'form-control ajax-update',
                        'field' => 'use_flag',
                        'data-id' => $model->id,
                        'old-value' => $model->use_flag,
                        'style' => 'width:120px;margin:0 auto;'
                    ]);
                    return $str = "<div class='text'>{$icon}</div>" . "<div class='input' style='display: none'>{$dropDownList}</div>";
                }
            ],

            [
                    'header' => '操作',
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{next} &nbsp;&nbsp;| &nbsp;&nbsp;{update} {delete}',
                    'buttons' => [
                        'next' => function($url ,$model) {
                            return Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;', ['ott-channel/index', 'sub-id' => $model->id], [
                                'class' => 'btn btn-success btn-xs'
                            ]);
                        },

                    ]
            ],

        ],
    ]); ?>


<div>

    <?php if(isset($mainClass)): ?>
        <?php $version = (new \backend\models\Cache())->getCacheVersion($mainClass->name); ?>
        <?= Html::a("生成缓存($version)" , '#', [
            'url' => Url::to(['sub-class/generate-cache', 'id' => $mainClass->id]),
            'class' => 'btn btn-success',
            'id' => 'cache-btn',
            'data-toggle' => 'modal',
            'data-target' => '#cache-modal',
        ])  ?>
    <?php endif; ?>

    <?= Html::a('重新排列频道号', ['sub-class/reset-number','main_class_id' => $mainClass->id], ['class' => 'btn btn-primary']) ?>

    <?= Html::a('批量导入', Url::to(['sub-class/import-via-text', 'mode' => 'keywordChannel']), ['class' => 'btn btn-info'])  ?>

    <?= Html::button("批量删除",['class' => 'gridview btn btn-danger']) ?>

    <?= Html::a('返回上一级', Url::to(['main-class/index']), ['class' => 'btn btn-default']) ?>

</div>
</div>

<?php
    Modal::begin([
        'id' => 'create-modal',
        'header' => '<h4 class="modal-title">创建二级分类</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    Modal::end();

    Modal::begin([
        'id' => 'cache-modal',
        'size' => Modal::SIZE_SMALL,
        'header' => '<h4 class="modal-title">操作提示</h4>',
        'footer' => '',
    ]);

    echo "<h4><i class='fa fa-spinner fa-pulse'> </i> 生成缓存中</h4>";

    Modal::end();
?>

<?php
$batchDelete = Url::to(['sub-class/batch-delete']);
$requestUrl = Url::toRoute(['sub-class/create', 'main_id' => $mainClass->id]);
$updateUrl = Url::to(['sub-class/update', 'field' => 'sort', 'id'=>'']);
$csrfToken = Yii::$app->request->csrfToken;

$requestJs=<<<JS
        $(document).on("click", ".gridview", function () {
                    var keys = $("#grid").yiiGridView("getSelectedRows");
                    window.location.href = '{$batchDelete}' + '&id=' + keys.join(',');
        });
        $(document).on('click', '#create', function() {
                $.get('{$requestUrl}', {},
                    function (data) {
                        $('.modal-body').html(data);
                    }
                )
            })
         var commonJS = {
               'callback':function(obj,value=false) {
                   var inp = obj.parent();
                   var td = inp.parent();
                   if (value) {
                       td.find('.text').text(value).show();
                   } else {
                       td.find('.text').show();
                   }
                   inp.hide();  
               }
           };   
    
        $('.ajax-update').change(function(event){
            var that = $(this); 
            var newValue = $(this).val();
            var oldValue = $(this).attr('value');
            var field = $(this).attr('field');
            
            var id = $(this).attr('data-id');
            var url = '{$updateUrl}' + id;
           
            if (newValue === oldValue)  return false;
            
            $.post(url, {field:field,value:newValue,_csrf:'{$csrfToken}'}, function(data){
                   if(field == 'sort' || field == 'use_flag') {
                       window.location.reload();
                   } else {
                       commonJS.callback(that, newValue);
                   }
            });
            
        });
    
         $('.ajax-td').click(function() {
                var td = $('.ajax-td');
                var index = $(this).index();
                $.each(td, function(){
                     if ($(this).index() !== index) {
                         $(this).find('.input').hide();
                         $(this).find('.text').show(); 
                     }
                    
                });
                $(this).find('.input').show();
                $(this).find('.text').hide(); 
         });    
        
        $('#cache-btn').click(function() {
            var link = $(this).attr('url');
            setTimeout(function(){
                window.location.href = link;
            }, 500);
        });
        
JS;

$this->registerJs($requestJs);
?>
