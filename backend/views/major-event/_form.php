<?php

use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Jsblock;
use backend\assets\MyAppAsset;

MyAppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\MajorEvent */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']);

?>

<style>
    .select2-container--open{
        z-index:9999999
    }
    .layui-laydate-list>li {width: 50%;}
    .layui-laydate-list li:nth-child(3) {
        display: none;
    }
</style>

<div class="major-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- 手动添加  -->
    <div class="tab-pane fade in active" id="manual" style="margin-top: 20px;">
        <div class="col-md-6">
            <?= $form->field($model, 'time')->textInput(['class' => 'form-control date']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'base_time')->textInput(['class' => 'form-control time']) ?>
        </div>



        <div class="col-md-6">
            <label >赛事选择</label>
            <div class="input-group" style="position: relative">
                <input type="hidden" name="event_info" class="event_info">
                <input type="hidden" name="teamA" class="teamA">
                <input type="hidden" name="teamB" class="teamB">
                <input type="text" class="form-control event-text" placeholder="选择赛事" readonly="readonly">
                    <span class="input-group-btn">
                                <?= Html::button('选择赛事', [
                                    'class' => 'btn btn-info btn-search event-search',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#event-modal',
                                    'data-index' => 0
                                ]) ?>
                    </span>
            </div>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true , 'placeholder' => '例:世界杯小组赛A组']) ?>
        </div>

        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>频道</th>
                    <th width="200">语言</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="channel_table">
                <tr>
                    <td>

                        <div class="input-group" style="position: relative">
                            <input type="hidden" name="main_class[]" class="main_class">
                            <input type="hidden" name="channel_id[]" class="channel_id">
                            <input type="hidden" name="language[]" class="language">
                            <input type="text" name="channel_name[]" class="form-control channel" placeholder="选择频道" readonly="readonly">
                            <span class="input-group-btn">
                                        <?= Html::button('查找', [
                                            'class' => 'btn btn-info btn-search bind',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#bind-modal',
                                            'data-index' => 0
                                        ]) ?>

                                    </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group" style="position: relative">

                            <input type="text" name="language_name[]" class="form-control  language-input" placeholder="选择语言" readonly="readonly">
                            <span class="input-group-btn">
                                        <?= Html::button('选择', [
                                            'class' => 'btn btn-default language-button',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#language_modal',
                                            'data-index' => 0
                                        ]) ?>

                                    </span>
                        </div>


                    </td>
                    <td>
                        <?= Html::button('重置', ['class' => 'btn btn-warning del']) ?>
                        <button class="btn btn-info add"> + </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-12">

            <?php $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
                <?= Html::a('返回', ['major-event/index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>


    <div class="col-md-12">

    </div>
    <?php ActiveForm::end(); ?>
</div>



<!-- 选择语言 -->
<div>
    <?php
    Modal::begin([
        'id' => 'language_modal',
        'size' => Modal::SIZE_SMALL,
        'header' => '<h4 class="modal-title">选择语言</h4>',
        'footer' => '',
    ]);
    echo "<div class='modal-body'>" . \common\widgets\lang\LangWidget::widget() . "</div>";
    Modal::end();
    ?>
</div>


<!--  赛事选择modal:start  -->
<div>
    <?php
        Modal::begin([
           'id' => 'event-modal',
           'header' => '<h4 class="modal-title">选择赛事</h4>',
            'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
        ]);
        Modal::end();
    ?>
</div>
<!--  赛事选择model:end  -->

<!--选择频道modal-->
<div>
    <?php
    Modal::begin([
        'id' => 'bind-modal',
        'size' => Modal::SIZE_DEFAULT,
        'header' => '<h4 class="modal-title">选择频道</h4>',
        'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
    ]);
    Modal::end();
    ?>
</div>

<?php

$eventUrl = Url::to(['ott-event/dropdownlist']);
$requestUrl = Url::to(['parade/bind']);
$requestJs=<<<JS
    
    //计数器
    var indexCounter = {
        language:0,
        channel:0,
        plus:function(field) {
            if (field === 'lang') {
                this.language = this.language + 1;
            } else {
                 this.channel = this.channel + 1;
            }
        },
        reduce:function(field) {
            
            if (field === 'lang') {alert();
                if (this.language > 0) this.language = this.language - 1;
            } else {
                if (this.channel > 0) this.channel = this.channel - 1; 
            }
        }
    };

    //监听modal狂
    var modal = $("#language_modal");
    modal.on("show.bs.modal", function(event) {    
        // 这里的btn就是触发元素，即你点击的删除按钮
        var btnThis = $(event.relatedTarget); //触发事件的按钮  
        indexCounter.language = btnThis.data('index');   //解析出data-id的内容  
    });
    
   //语言选择框
   $(".fastbannerform__country").on('select2:select', function (e) {
       
        $(this).val('');
        var data = e.params.data;
        // console.log(data);
        $('.channel_table tr').eq(indexCounter.language).find('.language-input').val(data.text);
        $('.language').eq(indexCounter.language).val(data.id);
        //关闭Modal
        $('#language_modal').modal('hide');
            
    });
        
    //弹出频道选择
    $(document).on('click', '.bind', function() {
                indexCounter.channel = $(this).attr('data-index');
                $.get('{$requestUrl}', {},
                    function (data) {
                        $('#bind-modal').find('.modal-body').css('min-height', '70px').html(data);
             } 
         )
    });
        
     //频道选择事件
     $(document).on('click', '.choose', function() {
                var index = indexCounter.channel;
                var main_class = $('#main-class').find('option:selected').text();
                
                var channel = $('#channel').find('option:selected');
                var text = channel.text(),
                    channel_id = channel.val();
                
                $('.channel').eq(index).val(text);
                $('.main_class').eq(index).val(main_class);
                $('.channel_id').eq(index).val(channel_id);
                
                //隐藏
                $('#bind-modal').modal('hide');
                $('.channel_table tr').eq(index).find('.language-button').eq(0).click();
                return false;
     });
     
      //复制一行
     $(document).on('click', '.add', function() {
           
            var index = indexCounter.channel;
            
            //检查是否填写完毕
            var nodeObject = $('.channel_table tr').eq(index);
                if (!nodeObject.find('.channel').eq(0).val()) {
                    nodeObject.find('.bind').click(); return false;
                }
                
            //重置值    
            var node = nodeObject.clone(),
                lang_button = node.find('.language-button'), 
                bind_button = node.find('.bind');
                
                node.find('input').val('');    
                lang_button.attr('data-index', parseInt(lang_button.attr('data-index')) + 1);
                bind_button.attr('data-index', parseInt(bind_button.attr('data-index')) + 1);
                
                indexCounter.plus('channel');
                indexCounter.plus('lang');
            
                //复制一行
                $('.channel_table').append(node);
            
                //关闭Modal
                $('#bind-modal').modal('hide');    
            
                return false;
     });
     
     $(document).on('click', '.del', function() {
            
            $(this).parent().parent().find('input').val('');   
     });
     
     //赛事信息弹出框
     $('.event-search').click(function() {
          
             $.get('{$eventUrl}',function(data) {
                  $('#event-modal').find('.modal-body').css('min-height', '80px').html(data);
             });
     });
     
     //赛事信息选择框
     $(document).on('click', '.event-choose', function() {
            var event = $('#event').find('option:selected').text(),
                teamA = $('#teamA').find('option:selected').text(),
                teamB = $('#teamB').find('option:selected').text();
            var text = event + ':' + teamA + ' VS ' + teamB;
            
            if (teamA === teamB) {
                alert('两支队伍不能一样');
                return false;
            }
            
            $('input[name=event_info]').val(event);
            $('input[name=teamA]').val(teamA);
            $('input[name=teamB]').val(teamB);
            $('.event-text').val(text);
            $('#event-modal').modal('hide');
     });
     
JS;

$this->registerJs($requestJs);

?>

<?php Jsblock::begin() ?>
<script>
    laydate.render({
        elem: '.date'
    });
    laydate.render({
        elem: '.dates'
    });
    laydate.render({
        elem: '.time'
        ,type: 'time'
        ,range: true
    });
    laydate.render({
        elem: '.times'
        ,type: 'time'
        ,range: true
    });
</script>
<?php Jsblock::end() ?>

