$('.switch-component').change(function() {

   var value  = $(this).prop('checked'),
       method = $(this).data('type'),
       url    = $(this).data('link'),
       id     = $(this).data('id'),
       fieldName  = $(this).data('idField'),
       field  = $(this).data('field'),
       success= $(this).data('success'),
       error  = $(this).data('error'),
       csrf   = $(this).data('csrf');

       value = value ? '1' : '0';

   if (method === 'POST') {
      $.post(url, {fieldName:id,field:field,value:value,_csrf:csrf}, function (e) {
        console.log(e.status);
          if (e.status =='success') {
            layer.msg(success);
          } else {
            layer.msg(error);
          }
      }).fail(function(xhr, status, error) {
            layer.msg('服务器内部错误');
      });
   } else {
      $.get(url, {fieldName:id}, function(e) {
        if (e.status == 'success') {
          layer.msg(success);
        } else {
          layer.msg(error);
        }
      });
  }

  return false;
});