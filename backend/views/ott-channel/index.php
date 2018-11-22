<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OttChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Channel List');
$this->params['breadcrumbs'][] = ['label' => $mainClass->zh_name? $mainClass->zh_name:$mainClass->name, 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = ['label' => $subClass->zh_name?$subClass->zh_name:$subClass->name, 'url' => Url::to(['sub-class/index', 'main-id' => $mainClass->id])];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/statics/themes/default-admin/plugins/layer/layer.min.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<style>
    .grid-view th{text-align: center;}
    .grid-view td {
        text-align: center;
        vertical-align: middle !important;
    }
</style>

<div class="ott-channel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create','sub-id' => $subClass->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

        try {
            echo GridView::widget([
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

                    [
                        'attribute' => 'image',
                        'filter' => false,
                        'format' => ['image',['width'=>80,'height'=>60]],
                        'options' => ['style' => 'width:85px;'],
                        'value' => function($model) {
                            return \common\components\Func::getAccessUrl($model->image);
                        }
                    ],
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

                    /*[
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
                    ],*/


                    [
                        'attribute' => 'channel_number',

                        'options' => ['style' => 'width:70px;'],
                        'filter' => false
                    ],


                    [
                        'attribute' => 'sort',
                        'filter' => false,
                        'contentOptions' => ['class' => 'ajax-td'],
                        'options' => ['style' => 'width:60px;'],
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
                        'headerOptions' =>['class' => 'col-md-1'],
                        'filter' => false,
                        'format' => 'raw',
                        'value' => function($model) {
                            return \common\widgets\switchInput\SwitcherInputWidget::widget([
                                'id' => $model->id,
                                'field' => 'use_flag',
                                'url' => \yii\helpers\Url::to(['ott-channel/update', 'id' => $model->id]),
                                'defaultCheckedStatus' => $model->use_flag,
                                'successTips' => '操作成功',
                                'errorTips'   => '操作失败'
                            ]);
                        }
                    ],


                    //'alias_name',

                    [
                        'class' => 'common\grid\MyActionColumn',
                        'size' => 'btn-sm',
                        'template' => '{channel} &nbsp;|&nbsp;{push-recommend} {push-banner} {view} {update} {delete}',
                        'buttons' => [
                            'channel' => function($url, $model, $key) {
                                return Html::a("&nbsp;&nbsp;<i class='glyphicon glyphicon-link'></i>". Yii::t('backend', 'Link')."&nbsp;&nbsp;", null, [
                                    'class' => 'btn btn-success btn-sm load-link',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#links-modal',
                                    'data-id' => $model->id,
                                ]);
                            },
                            'push-banner' => function($url, $model, $key) {
                                return Html::a("&nbsp;<i class='glyphicon glyphicon-picture'></i>&nbsp;", Url::to(Url::to(['ott-banner/create', 'channel_id' => $model->id])), [
                                    'class' => 'btn btn-default btn-sm'
                                ]);
                            },
                            'push-recommend' => function($url, $model, $key) {
                                $class = $model->is_recommend ? 'btn-warning' : 'btn-default';

                                return Html::a("&nbsp;<i class='glyphicon glyphicon-thumbs-up'></i>&nbsp;", Url::to(Url::to(['ott-channel/update', 'id' => $model->id, 'field'=>'is_recommend'])), [
                                    'class' => 'btn btn-sm ' . $class
                                ]);
                            }
                        ],
                        'options' => [ 'style' => 'width:350px;']
                    ],
                ],
            ]);
        }  catch (\Exception $e) {

        }

    ?>
 </div>

<div>

<?= Html::a(Yii::t('backend', 'Rearrange channel numbers'), ['sub-class/reset-number','main_class_id' => $mainClass->id], ['class' => 'btn btn-primary']) ?>

<?= Html::button(Yii::t('backend', 'Batch Deletion'),['class' => 'gridview btn btn-danger' ]) ?>

<?= Html::a(Yii::t('backend', 'Go Back'), Url::to(['sub-class/index', 'main-id' => $mainClass->id]), ['class' => 'btn btn-default']) ?>

</div>

<!--链接modal部分 开始-->
<?php

Modal::begin([
         'id' => 'links-modal',
          'size' => Modal::SIZE_LARGE,
          'header' => '<h4 class="modal-title">'. Yii::t('backend', 'Link').'</h4>',
          'footer' => '<a href="#" class="btn btn-info create-link" data-id="0">'. Yii::t('backend', 'Create Link') .'</a>&nbsp;<a href="#" class="btn btn-default" data-dismiss="modal">'. Yii::t('backend', 'close').'</a>',
]);


$schemeUrl = Url::to(['api/scheme']);
$requestUrl = Url::to(['ott-link/index']);
$switchUrl = Url::to(['ott-link/update', 'field'=>'use_flag']);
$updateUrl = Url::to(['ott-link/update', 'field'=>'scheme_id', 'id' => '']);
$delLinkUrl = Url::to(['ott-link/delete']);
$createLinkUrl = Url::to(['ott-link/create']);
$updateLinkUrl = Url::to(['ott-link/update']);

$modify_text = Yii::t('backend', 'Editing Scheme');
$switch_text = Yii::t('backend', 'Available switch');
$update_text = Yii::t('backend', 'Update');
$delete_text = Yii::t('backend', 'Delete');
$soft_text = Yii::t('backend', 'Soft');
$hard_text = Yii::t('backend', 'Hard');
$all_text = Yii::t('backend', 'All Schemes');

$success = Yii::t('backend', 'Success');

$requestJs=<<<JS
    $('.load-link').click(function(){
        $('.modal-lg').css('width','99%');
        $('.modal-body').css('min-height','100px'); 
        $('.create-link').attr('data-id', $(this).attr('data-id')).show();
         
        
        $.getJSON('{$requestUrl}', {channel_id:$(this).attr('data-id')}, function(data) {
            var table = '<table class="table table-bordered"><thead><th style="width:30px;"><i class="fa fa-dot-circle-o"></th><th><i class="fa fa-link"></th><th width="50px"><i class="fa fa-key"></th><th width="50px"><i class="fa fa-tv"></i></th><th width="4px"><i class="fa fa-photo"></th><th width="80px"><i class="fa fa-flag"></i></th><th width="65px"><i class="fa fa-sort"></i></th><th style="width:200px;"><i class="fa fa-cog fa-fw"></th></tr></thead><tbody>';
            var tr = '';
            
            $.each(data,function(){
                    var schemeText = $(this).attr('schemeText').split(',')
                    var schemeString = ''
                    schemeText.forEach(function(v, k) {
                        if (v === '全部') {
                          schemeString += '<span style="width:114px;font-size:1px;margin:1px 1px;" class="btn btn-xs btn-info">{$all_text}</span>'
                        } else {
                          schemeString += '<span style="width:114px;font-size:1px;margin:1px 1px;" class="btn btn-xs btn-default">' + v + "</span>"
                        }
                        
                        if ((k+1) % 3 === 0 ) {
                          schemeString += '<br/>'
                        }
                    })
                    tr += '<tr  link-id="' +  $(this).attr('id')  + '">';
                    // tr += '<td style="vertical-align:middle;">' +schemeString + '</td>';
                    tr += '<td style="vertical-align:middle;">' + $(this).attr('source') + '</td>';
                    tr += '<td style="word-wrap:break-word;max-width:150px;">' + $(this).attr('link') + '</td>';
                    tr += '<td style="vertical-align:middle;">' + $(this).attr('method') + '</td>';
                    tr += '<td style="vertical-align:middle;">' + ($(this).attr('decode') === '0' ? '{$soft_text}':'{$hard_text}') + '</td>';
                    tr += '<td style="vertical-align:middle;">' + $(this).attr('definition') + '</td>';
                    tr += '<td style="vertical-align:middle;" class="use-flag"><button class="btn btn-default btn-xs use-switch">' + $(this).attr('use_flag_text') + '</button></td>';
                    tr += '<td style="vertical-align:middle;" ><input class="link-sort form-control" data-id="'+ $(this).attr('id') +'" value="'+ ($(this).attr('sort')) +'">' + '</td>';
                    tr += '<td style="vertical-align:middle;"><button class="btn btn-info btn-xs change-scheme" scheme-id=' + $(this).attr('scheme_id') + ' data-id='+ $(this).attr('id') +'>{$modify_text}</button>&nbsp;&nbsp;<button class="btn btn-warning btn-xs link-edit">{$update_text}</button>&nbsp;<button class="btn btn-danger btn-xs link-del">{$delete_text}</button></td></tr>';
            });
                
            table += tr;
            table += '</tbody></table>';
            $('.modal-body').html(table)
            
        });
    });
        
    $('body').on('click', '.use-switch' ,function(){
        var tr = $(this).parent().parent();
        var link_id = tr.attr('link-id');
        $.getJSON('{$switchUrl}', {id:link_id}, function(back) {
             $(tr).find('.use-switch').html(back.msg);
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
       
    }).on('click','.create-link', function() {
        var channel_id = $(this).attr('data-id');
        $.get('{$createLinkUrl}',{id:channel_id}, function(form) {
            $('.modal-lg').css('width','70%');
            $('.modal-body').html(form).css('min-height','390px'); 
            $('.create-link').hide();
        });
        
    }).on('click','.link-edit', function() {
         var tr = $(this).parent().parent();
         var link_id = tr.attr('link-id');
         $.get('{$updateLinkUrl}',{id:link_id,modal:'modal'}, function(form) {
            $('.modal-lg').css('width','70%');
            $('.modal-body').html(form).css('min-height','370px'); 
        });
         
    }).on('change', '.link-sort', function() {
        var id = $(this).attr('data-id'),
            data = {value:$(this).val()};
        
        $.post('{$updateLinkUrl}' + '&field=sort&id=' + id,data, function() {
             layer.msg('{$success}');
        });
    });


JS;

$this->registerJs($requestJs);

Modal::end()

?>
<!--链接modal部分 结束-->

<?php
$batchDelete = Url::to(['ott-channel/batch-delete']);
$updateChannelUrl = Url::to(['ott-channel/update', 'id'=>'']);
$csrfToken = Yii::$app->request->csrfToken;
$confirm_Text = Yii::t('backend', 'Are you sure to save your changes?');
$yes = Yii::t('backend', 'Yes');
$cancel = Yii::t('backend', 'Cancel');
$success = Yii::t('backend', 'Success');
$delete_text = Yii::t('backend', 'Are you sure?');

$requestJs=<<<JS
    
    $(document).on("click", ".gridview", function () {
                if (confirm('{$delete_text}')) {
                   var keys = $("#grid").yiiGridView("getSelectedRows");
                   window.location.href = '{$batchDelete}' + '&id=' + keys.join(',');
                }
    });
    
   var commonJS = {
       'callback':function(obj,value) {
           var inp = obj.parent();
           var td = inp.parent();
           td.find('.text').text(value).show();
           inp.hide();  
         
       },
       'callback2':function(obj, value) {
          var inp = obj.parent().eq(0);
          inp.val(value);
          inp.html(inp.html())
          console.log(inp);
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
        if (field === 'sort')  {
            $.post(updateChannelUrl, {field:field,value:newValue,_csrf:'{$csrfToken}'});
            layer.msg('{$success}');
            return;
        }
        
        window.layer = layer;
          
        layer.confirm('{$confirm_Text}', {
              title: '',
              btn: ['{$yes}', '{$cancel}'] //按钮
            }, function(){
              
          $.post(updateChannelUrl, {field:field,value:newValue,_csrf:'{$csrfToken}'}, function(data){
               if(field == 'use_flag') {
                   window.location.reload();
                    return false;
               } 
                commonJS.callback(that, newValue);
              })
                window.layer.closeAll()
              
            }, function() {
               if(field == 'sort' || field == 'use_flag') {
                    commonJS.callback2(that, oldValue);
            } else {
                  commonJS.callback(that, oldValue);
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
                
          })
            $(this).find('.input').show();
            $(this).find('.text').hide(); 
     });

   
JS;

$this->registerJs($requestJs);
?>





