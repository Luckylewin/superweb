$('.ajax-input').change(function() {

  var value  = $(this).val(),
     url    = $(this).data('link'),
     id     = $(this).data('id'),
     fieldName  = $(this).data('idName'),
     field  = $(this).data('field'),
     success= $(this).data('success'),
     error  = $(this).data('error'),
     csrf   = $(this).data('csrf');

  $.post(url, {fieldName:id,field:field,value:value,_csrf:csrf}, function (e) {

    if (e.status =='success') {
      layer.msg(success);
    } else {
      layer.msg(error, {icon:2});
    }
  }).fail(function(xhr, status, error) {
    layer.msg('服务器内部错误');
  });


});