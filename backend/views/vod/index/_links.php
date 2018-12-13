<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/12
 * Time: 13:24
 */

$js=<<<JS

$(document).on('click', '.frame-open', function() {

  var url = $(this).data('link');
  var title = $(this).data('name');
  var side = window.parent.document.getElementById('side-menu');
  var top = '120px';
  var left = side.offsetWidth + 20 + 'px';
  
  layer.open({
          title:title,
          type: 2,
          area: ['1124px', '600px'],
          offset: [top, left],
          anim: 2,
          fixed: true, //不固定
          maxmin: true,
          content: url
      });
       
      return false;
});

JS;

$this->registerJs($js);