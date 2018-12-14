<?php
use yii\bootstrap\Modal;
use yii\helpers\Url;


Modal::begin([
    'id' => 'create-modal',
    'header' => '<h4 class="modal-title">'. Yii::t('backend', 'Create a secondary classification').'</h4>',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">'. Yii::t('backend', 'close') .'</a>',
]);
Modal::end();

Modal::begin([
    'id' => 'cache-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">'. Yii::t('backend', 'Operation prompt') . '</h4>',
    'footer' => '',
]);

echo "<h4><i class='fa fa-spinner fa-pulse'> </i>" . Yii::t('backend', 'Generating cache') . "</h4>";

Modal::end();

$batchDelete = Url::to(['sub-class/batch-delete']);
$requestUrl = Url::toRoute(['sub-class/create', 'main_id' => $mainClass->id]);
$updateUrl = Url::to(['sub-class/update', 'field' => 'sort', 'id'=>'']);
$csrfToken = Yii::$app->request->csrfToken;
$confirmText = Yii::t('backend', 'Are you sure?');

$requestJs=<<<JS
        $(document).on("click", ".gridview", function () {
          if (confirm('{$confirmText}')) {
              var keys = $("#grid").yiiGridView("getSelectedRows");
              window.location.href = '{$batchDelete}' + '&id=' + keys.join(',');
          } 
                    
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
