<?php

use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/19
 * Time: 9:40
 */

$url = Url::to(['vod/stick', 'id' => ''], true);

$requestJs=<<<JS
    
     $(document).on('click', '.stick', function() {
                var id = $(this).data('id');
                var is_top = $(this).data('top');
                var _this = $(this);
               
                $.getJSON('{$url}' + id,function () {
                        if (is_top) {
                           layer.msg('已取消置顶');
                           _this.data('top', 0).removeClass('is_top').addClass('btn-default');
                          
                        } else {
                           layer.msg('置顶成功');
                           _this.data('top', 1).removeClass('btn-default').addClass('is_top').css('font-color','#fff');
                        }
                    }
                );
                
               return false;
  })
JS;

$this->registerJs($requestJs, \yii\web\View::POS_END);