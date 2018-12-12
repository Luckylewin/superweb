<?php
use yii\bootstrap\Modal;
use yii\helpers\Url;

//影片详情
Modal::begin([
    'id' => 'show-modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="modal-title">详情</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['vod/view']);
$requestJs=<<<JS
    
     $(document).on('click', '.btn-show', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                $.get('{$requestUrl}', {'id':id},
                    function (data) {
                        $('#show-modal .modal-title').text(name);
                        $('#show-modal .modal-body').css('min-width', '700px').css('min-height', '200px').html(data);
                    }
                )
            })
JS;

$this->registerJs($requestJs, \yii\web\View::POS_END);
Modal::end();
?>