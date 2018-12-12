<?php
use yii\bootstrap\Modal;
use yii\helpers\Url;

//绑定方案号
Modal::begin([
    'id' => 'setting-scheme-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">设置方案号</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['vod/bind-scheme']);
$requestJs=<<<JS
     $(document).on('click', '.bind', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}', {'id':id},
                    function (data) {
                        $('#setting-scheme-modal .modal-body').css('min-height', '200px').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs);
Modal::end();

?>