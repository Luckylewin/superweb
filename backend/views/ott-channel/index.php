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
    .grid-view td{
        text-align: center;
    }
    .modal-lg {
        /* respsonsive width */
        width: 100%;
    }
</style>

<div class="ott-channel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加频道', ['create','sub-id' => $subClass->id], ['class' => 'btn btn-success']) ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
                'class' => 'table table-bordered table-hover',
        ],
        'options' => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            [
                "class" => "yii\grid\CheckboxColumn",
                "name" => "id",
            ],
            ['class' => 'yii\grid\SerialColumn'],
            //'image',
            //'id',
            //'sub_class_id',
            [
                'attribute' => 'name',
                'contentOptions' => ['class' => 'ajax-td'],
                'options' => ['style' => 'min-width:120px;'],
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
                'contentOptions' => ['class' => 'ajax-td'],
                'options' => ['style' => 'min-width:120px;'],
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
                'attribute' => 'keywords',
                'contentOptions' => ['class' => 'ajax-td'],
                'options' => ['style' => 'min-width:120px;'],
                'format' => 'raw',
                'value' => function($model) {
                    $str =  Html::textInput('sort', $model->keywords, [
                        'class' => 'form-control ajax-update',
                        'field' => 'keywords',
                        'data-id' => $model->id,
                        'old-value' => $model->keywords
                    ]);
                    return $str = "<div class='text'>{$model->keywords}</div>" . "<div class='input' style='display: none'>$str</div>";
                }
            ],
            [
                'attribute' => 'sort',
                'contentOptions' => ['class' => 'ajax-td'],
                'options' => ['style' => 'width:70px;'],
                'format' => 'raw',
                'value' => function($model) {
                    return \yii\bootstrap\Html::textInput('sort', $model->sort, [
                        'class' => 'form-control ajax-update',
                        'data-id' => $model->id,
                        'field' => 'sort',
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
            'channel_number',
            //'alias_name',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{channel} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;{view} {update} {delete}',
                    'buttons' => [
                            'channel' => function($url, $model, $key) {
                                return Html::a("&nbsp;&nbsp;<i class='glyphicon glyphicon-link'></i>&nbsp;&nbsp;", null, [
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

<div>

<?= Html::a('重新排列频道号', ['sub-class/reset-number','main_class_id' => $mainClass->id], ['class' => 'btn btn-primary']) ?>

<?= Html::button("批量删除",[
    'class' => 'gridview btn btn-danger',
]) ?>

<?= Html::a('返回上一级', Url::to(['sub-class/index', 'main-id' => $mainClass->id]), ['class' => 'btn btn-default']) ?>

<?php
    $batchDelete = Url::to(['ott-channel/batch-delete']);
    $updateChannelUrl = Url::to(['ott-channel/update', 'id'=>'']);
    $csrfToken = Yii::$app->request->csrfToken;

    $requestJs=<<<JS
    
    $(document).on("click", ".gridview", function () {
                var keys = $("#grid").yiiGridView("getSelectedRows");
                var url = '{$batchDelete}' + '&id=' + keys.join(',');
                window.location.href = url;
            });
    
   var commonJS = {
       'callback':function(obj,value) {
           var inp = obj.parent();
           var td = inp.parent();
           td.find('.text').text(value).show();
           inp.hide();  
       }
   };   

   $('.ajax-update').change(function(){
        var newValue = $(this).val();
        var oldValue = $(this).attr('value');
        var field = $(this).attr('field');
        
        var id = $(this).attr('data-id');
        var updateChannelUrl = '{$updateChannelUrl}' + id;
       
        if (newValue === oldValue) {
            return false;
        }
        
      var that = $(this);
        $.post(updateChannelUrl, {field:field,value:newValue,_csrf:'{$csrfToken}'}, function(data){
               if(field == 'sort' || field == 'use_flag') {
                   window.location.reload();
                   return false;
               }
               commonJS.callback(that, newValue);
        })
    });
   
     $('.ajax-td').click(function() {
            var td = $('.ajax-td');
          
            $(this).find('.input').show().focus().select();
            $(this).find('.text').hide(); 
     });

   
JS;

    $this->registerJs($requestJs);
?>
</div>

<!--链接modal部分 开始-->
<?php

Modal::begin([
         'id' => 'links-modal',
          'size' => Modal::SIZE_LARGE,
          'header' => '<h4 class="modal-title">链接</h4>',
          'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

$requestUrl = Url::to(['ott-link/index']);
$switchUrl = Url::to(['ott-link/update', 'field'=>'use_flag']);
$schemeUrl = Url::to(['api/scheme']);
$updateUrl = Url::to(['ott-link/update', 'field'=>'scheme_id', 'id' => '']);
$delLinkUrl = Url::to(['ott-link/delete']);

$requestJs=<<<JS
    $('.load-link').click(function(){
        $.getJSON('{$requestUrl}', {channel_id:$(this).attr('data-id')}, function(data) {
            var table = '<table class="table table-bordered"><thead><tr><th>方案</th><th>来源</th><th width="120px">链接</th><th>算法</th><th width="50px">解码</th><th width="60px">清晰度</th><th width="70px">状态</th><th style="width:300px;">操作</th></tr></thead><tbody>';
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
                    tr += '<td><button class="btn btn-info btn-xs change-scheme" scheme-id=' + $(this).attr('scheme_id') + ' data-id='+ $(this).attr('id') +'>修改方案</button>&nbsp;<button class="btn btn-info btn-xs disabled">脚本开关</button>&nbsp;<button class="btn btn-primary btn-xs use-switch">可用开关</button>&nbsp;&nbsp;<button class="btn btn-danger btn-xs link-del">删除</btn></td></tr>';
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
        
    }).on('click', '.link-del',function(){
         var tr = $(this).parent().parent();
         var link_id = tr.attr('link-id');
         $.getJSON('{$delLinkUrl}', {id:link_id}, function(back) {
             tr.css('background','#ccc').slideUp(200);
        });
    }).on('click', '.change-scheme', function() {
       
        var link_id = $(this).attr('data-id');
        var scheme_id = $(this).attr('scheme-id');
        var schemeArr = scheme_id.split(',');
        
        var text = "";
        $.getJSON('{$schemeUrl}', {}, function(data){
            text += "<div class='well' id='mywell'>";
            $.each(data, function() {
                if (schemeArr.indexOf($(this).attr('id')) >= 0 || schemeArr == 'all') {
                    text += "<label><input type='checkbox' checked=checked value='" + $(this).attr('id') +"'>"+ $(this).attr('schemeName') +"</label>";
                } else {
                    text += "<label><input type='checkbox' value='" + $(this).attr('id') +"'>"+ $(this).attr('schemeName') +"</label>";
                }
                
            })
           
            text += '</div><br><div><button class="btn btn btn-info scheme-submit" data-id="' + link_id +'">修改</button></div>';
            $('.modal-body').html(text);
        })
      
    }).on('click', '.scheme-submit', function() {
        
       var chk_value = []; 
       var link_id = $(this).attr('data-id');
       var checkbox = $('#mywell').find('input[type=checkbox]:checked');
       $.each(checkbox, function() {
            chk_value.push($(this).val());   
       });
       
       $.post('{$updateUrl}' + link_id, {scheme:chk_value}, function(back){
            $('.modal-body').html("<h3><i style='color:green' class='glyphicon glyphicon-ok-circle'>修改成功</i></h3>");
            setTimeout(function(){
                $('#links-modal').modal('hide');
            },1500);
       
       })
       
    });


JS;

$this->registerJs($requestJs);

Modal::end()

?>
<!--链接modal部分 结束-->






