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
  layer.myWindows(url, title)
       
      return false;
});

JS;

$this->registerJs($js);