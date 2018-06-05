<?php

use yii\helpers\Html;
use common\models\MainClass;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$mainClass = MainClass::getDropdownList();

/* @var $this yii\web\View */
$this->title = '绑定频道';
$this->params['breadcrumbs'][] = ['label' => '预告列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parade-create">

    <?php ActiveForm::begin([
        'options' => [
            'class' => 'form-inline',
            'role' => 'form',
            'action' => Url::to(['parade/bind']),
            'method' => 'POST'
        ]
    ]) ?>

    <?= Html::hiddenInput('alias_name', $alias_name); ?>
    <div class="form-group">
        <label class="sr-only" for="main-class">一级分类</label>
        <?= Html::dropDownList('main-class', null, $mainClass, ['class' => 'form-control', 'id' => 'main-class', 'prompt' => '请选择', 'required' => true]); ?>
    </div>

    <div class="form-group">
        <label class="sr-only" for="main-class">一级分类</label>
        <?= Html::dropDownList('sub-class', null, [], ['class' => 'form-control', 'id' => 'sub-class', 'prompt' => '请选择', 'required' => true]); ?>
    </div>

    <div class="form-group">
        <label class="sr-only" for="main-class">一级分类</label>
        <?= Html::dropDownList('channel', null, [], ['class' => 'form-control', 'id' => 'channel', 'prompt' => '请选择', 'required' => true]); ?>
    </div>

        <button type="submit" class="btn btn-info">关联</button>
   <?php \yii\widgets\ActiveForm::end(); ?>

</div>

<?php

$subClassUrl = Url::to(['sub-class/drop-down-list']);
$channelUrl = Url::to(['ott-channel/drop-down-list']);

$requestJs=<<<JS
    var prompt = '<option value="">请选择</option>';

    $('#main-class').change(function() {
       var main_id = $(this).val();
       $.getJSON('{$subClassUrl}',{main_class_id:main_id}, function(data) {
          var sub_class = $('#sub-class');
              sub_class.html('');
              sub_class.append(prompt);
              $('#channel').html(prompt);
          $.each(data, function(index, value) {
              var node = '<option value="'+ index +'">' + value + '</option>';
              sub_class.append(node);
          });
       }); 
    });

    $('#sub-class').change(function() {
        var sub_id = $(this).val();
        $.getJSON('{$channelUrl}',{sub_class_id:sub_id}, function(data) {
          var channel = $('#channel');
              channel.html('');
              channel.append(prompt);
          $.each(data, function(index, value) {
              var node = '<option value="'+ index +'">' + value + '</option>';
              channel.append(node);
          });
       });        
    });
JS;

$this->registerJs($requestJs);

?>
