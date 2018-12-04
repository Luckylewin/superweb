<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

//更多操作
Modal::begin([
    'id' => 'more-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">更多操作</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

$requestJs=<<<JS
    
     $(document).on('click', '.btn-more', function() {
                var id = $(this).data('id');
                var recommend_url = $(this).data('recommend-url');
                var banner_url = $(this).data('banner-url');
                $('#more-modal .modal-body').css('height', '100px')
             
                $('#thumbs').attr('href', recommend_url);
                $('#banner').attr('href', banner_url);
                
     });
     
     
JS;

echo "<div class='col-md-10 col-md-offset-1 text-center'>";

echo Html::a(Html::tag('i',' 推荐推送', ['class'=>'fa fa-thumbs-o-up']), '', [
    'class' => 'btn btn-default btn-lg',
    'id' => 'thumbs'
]);

echo "&nbsp;";

echo Html::a(Html::tag('i',' Banner图推送', ['class'=>'glyphicon glyphicon-picture']), '', [
    'class' => 'btn btn-default btn-lg',
    'id' => 'banner'
]);

echo "&nbsp;</div>";

$this->registerJs($requestJs);
Modal::end();