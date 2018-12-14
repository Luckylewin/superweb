<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;

Modal::begin([
    'id' => 'download-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">操作提示</h4>',
    'footer' => '',
]);
echo "<h4><i class='fa fa-spinner fa-pulse'> </i> 请勿关闭,完成将提示下载</h4>";
Modal::end();

$exportUrl = Url::to(['main-class/export', 'id' => '']);
$exportImageUrl = Url::to(['main-class/export-image', 'main_class_id' => '']);
$queryIsDoneUrl = Url::to(['main-class/query-task', 'queue_id' => '']);

$js =<<<JS
    $(document).on('click', '.btn-export', function () {
      var keys = $("#grid").yiiGridView("getSelectedRows");
      window.location.href = '{$exportUrl}' + keys;
      return false;
    });
    
    $(document).on('click', '.btn-export-image', function () {
      $(this).attr('disabled', true);
      
      var keys = $("#grid").yiiGridView("getSelectedRows"),
          url = '{$exportImageUrl}' + keys,
          queryUrl = '{$queryIsDoneUrl}',
          _this = $(this);
      
      $.getJSON(url, function (e) {
            if (e.status && e.queue_id) {
                   queryUrl += e.queue_id;
                   $('#download-modal').modal();
        
                   var timer = setInterval(function() {
                      $.getJSON(queryUrl, function(e) {
                           if (e.status) {
                              clearInterval(timer);
                              $('#download-modal').modal('hide'); 
                              window.location.href = e.url;
                           } 
                      }); 
                   }, 10000)
              }  else {
                 clearInterval(timer);
                 alert('请选择分类');
                 _this.removeAttr('disabled');
              }
    });
      
       
      
      return false;
  });
    

JS;


$this->registerJs($js);

?>