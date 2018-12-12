<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;


//更多操作
Modal::begin([
    'id' => 'more-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">详情</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

echo "<div class='col-md-10 col-md-offset-1 text-center'>";
echo Html::button('<i class="glyphicon glyphicon-cog"></i> 设置方案号 ', [
    'class' => 'btn btn-default btn-lg bind',
    'data-toggle' => 'modal',
    'data-target' => '#setting-scheme-modal',
    'data-id' => '',

]);
echo "&nbsp;";
echo Html::a(Html::tag('i','Banner图推送', ['class' => 'fa fa-file-picture-o']), '', [
    'class' => 'btn btn-default btn-lg',
    'id' => 'bannerBtn',

]);
echo "&nbsp;";
echo Html::a(Html::tag('i','首页推荐', ['class' => 'fa fa-thumbs-up']), '', [
    'class' => 'btn btn-sm btn-default btn-lg',
    'id' => 'homeBtn'
]);
echo "&nbsp;</div>";

Modal::end();

$requestJs=<<<JS
     $(document).on('click', '.btn-more', function() {
                var id = $(this).data('id');
                var home_url = $(this).data('home-url');
                var banner_url = $(this).data('banner-url');
                $('#more-modal .modal-body').css('height', '100px')
                $('.bind').data('id', id).attr('data-id', id);
                $('#bannerBtn').attr('href', banner_url);
                $('#homeBtn').attr('href', home_url).attr('class', $(this).data('home-class'));
     });
JS;

$this->registerJs($requestJs);