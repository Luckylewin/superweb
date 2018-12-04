<!--链接modal部分 开始-->
<?php
use yii\bootstrap\Modal;
use yii\helpers\Url;

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
        $('#links-modal .modal-body').css('min-height','100px'); 
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
                    tr += '<td style="vertical-align:middle;"><button class="btn btn-primary btn-sm fa fa-cog change-scheme" scheme-id=' + $(this).attr('scheme_id') + ' data-id='+ $(this).attr('id') +'> {$modify_text}</button>&nbsp;&nbsp;<button class="btn btn-info fa fa-edit btn-sm link-edit"></button>&nbsp;<button class="btn btn-danger btn-sm fa fa-trash link-del"></button></td></tr>';
            });
                
            table += tr;
            table += '</tbody></table>';
            $('#links-modal .modal-body').html(table)
            
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
           
            text += '</div><br><div><button class="btn btn btn-primary col-md-12 text-center scheme-submit" data-id="' + link_id +'">修改</button></div>';
            $('#links-modal .modal-body').css('min-height','200px').html(text);
        })
      
    }).on('click', '.scheme-submit', function() {
        
       var chk_value = []; 
       var link_id = $(this).attr('data-id');
       var checkbox = $('#mywell').find('input[type=checkbox]:checked');
       $.each(checkbox, function() {
            chk_value.push($(this).val());   
       });
       
       $.post('{$updateUrl}' + link_id, {scheme:chk_value}, function(back){
            $('#links-modal .modal-body').html("<h3><i style='color:green' class='glyphicon glyphicon-ok-circle'>修改成功</i></h3>");
            setTimeout(function(){
                $('#links-modal').modal('hide');
            },1500);
       
       })
       
    }).on('click','.create-link', function() {
        var channel_id = $(this).attr('data-id');
        $.get('{$createLinkUrl}',{id:channel_id}, function(form) {
            $('.modal-lg').css('width','70%');
            $('#links-modal .modal-body').html(form).css('min-height','390px'); 
            $('.create-link').hide();
        });
        
    }).on('click','.link-edit', function() {
         var tr = $(this).parent().parent();
         var link_id = tr.attr('link-id');
         $.get('{$updateLinkUrl}',{id:link_id,modal:'modal'}, function(form) {
            $('.modal-lg').css('width','70%');
            $('#links-modal .modal-body').html(form).css('min-height','370px'); 
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