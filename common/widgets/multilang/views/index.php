<?php
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $id */
/* @var $modalShow */
/* @var $options */
?>

<?= Html::a(" " . $name, null, $options);
?>

<?php

if ($modalShow) {
    Modal::begin([
        'id' => 'language-modal',
        'size' => Modal::SIZE_DEFAULT,
        'header' => '<h4 class="fa fa-language modal-title"> 多语言设定</h4>',
        'footer' => '<a href="#" class="btn btn-default" style="background: #dedede; margin-top: 30px;" data-dismiss="modal">关闭</a>',
    ]);
    Modal::end();
}

$requestUrl = Url::to(['multi-lang/index']);
$requestJs=<<<JS
     $(document).on('click', '.language', function() {
                var id = $(this).data('id');
                var table = $(this).data('table');
                var field = $(this).data('field');
                
                $.get('{$requestUrl}', {id:id,table:table,field:field},
                    function (data) {
                        $('.modal-body').css('height', 'auto').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs, \yii\web\View::POS_READY, 'language-modal');


?>
