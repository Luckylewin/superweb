<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/12
 * Time: 13:23
 */

$sortUrl = \yii\helpers\Url::to(['vod/sort','vod_cid' => Yii::$app->request->get('VodSearch')['vod_cid']]);
$success = Yii::t('backend', 'Success');
$error = Yii::t('backend', 'operation failed');

$js=<<<JS
  var Sort = {
     sort_up_handler : function() {
                        var tr = $(this).parent().parent().parent(),
                          id = tr.attr('data-key'),
                          index = tr.index() + 1,
                          pre = index - 1,
                          _this = $(this);
                    
                        var preTr = $("#grid tbody tr:nth-child(" + pre + ")"),
                          preId = preTr.attr('data-key');
                    
                        $(this).addClass('current_up');
                    
                        preTr.insertAfter($("#grid tbody tr:nth-child(" + index + ")"));
                        $.getJSON('{$sortUrl}', {id:id,action:'up',compare_id:preId}, function(e) {
                            if (e.status === 'success') {
                              _this.parent().find('input').val(e.data.sort);
                              layer.msg('{$success}');
                            } else {
                              layer.msg('{$error}');
                            }
                          }
                        );
      },
      sort_down_handler : function() {
                        var tr = $(this).parent().parent().parent(),
                            id = tr.attr('data-key'),
                            index = tr.index() + 1,
                            next = index + 1,
                            _this = $(this);
                    
                        var nextTr = $("#grid tbody tr:nth-child(" + next + ")"),
                            nextId = nextTr.attr('data-key');
                    
                        $(this).addClass('current_down');
                    
                        $("#grid tbody tr:nth-child(" + index + ")").insertAfter($("#grid tbody tr:nth-child(" + next + ")"));
                        $.getJSON('{$sortUrl}', {id:id,action:'down',compare_id:nextId}, function(e) {
                            if(e.status === 'success') {
                              _this.parent().find('input').val(e.data.sort);
                              layer.msg('{$success}');
                            } else {
                              layer.msg('{$error}');
                            }
                          }
                        );
      },
      change_handler : function() {
                        var tr = $(this).parent().parent().parent(),
                            sort = $(this).val(),
                            id = tr.attr('data-key');
                    
                        $.getJSON("{$sortUrl}", {id:id,sort:sort,action:'appoint',compare_id:null}, function(e) {
                            if(e.status === 'success') {
                              layer.msg('{$success}');
                            } else {
                              layer.msg('{$error}');
                            }
                          }
                        );   
      }
  }
  
  $(document).on('load pjax:success', function() {
     $('.triangle_border_up').click(Sort.sort_up_handler)
     $('.triangle_border_down').click(Sort.sort_down_handler);
     $('.sort').change(Sort.change_handler);
  }).ready(function() {
     $('.triangle_border_up').click(Sort.sort_up_handler);
     $('.triangle_border_down').click(Sort.sort_down_handler);
     $('.sort').change(Sort.change_handler);
  });

JS;

$this->registerJs($js, \yii\web\View::POS_END);