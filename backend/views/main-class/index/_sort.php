
<?php
use yii\helpers\Url;

$updateUrl = Url::to(['main-class/update', 'field' => 'sort', 'id'=>'']);
$csrfToken = Yii::$app->request->csrfToken;

$requestJs=<<<JS
    $('.create-cache').click(function(){
        var url = $(this).attr('url');
        setTimeout(function(){
            window.location.href = url;
        },200);
    });
    $('.change-sort').blur(function(){
        var newValue = $(this).val();
        var oldValue = $(this).attr('value');
        
        var id = $(this).attr('data-id');
        var url = '{$updateUrl}' + id;
       
        if (newValue === oldValue) return false;
        
        $.post(url, {sort:newValue,_csrf:'{$csrfToken}'}, function(data){
              window.location.reload();
        })
    });

JS;

$this->registerJs($requestJs);

?>