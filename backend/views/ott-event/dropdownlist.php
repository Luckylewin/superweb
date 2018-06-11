<?php

use yii\helpers\Html;
use backend\models\OttEvent;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$event = OttEvent::getDropdownList();

/* @var $this yii\web\View */
$this->title = '赛事信息';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="parade-create">

        <div class="form-group">
            <label for="event">赛事列表</label>
            <?= Html::dropDownList('event', null, $event, ['class' => 'form-control', 'id' => 'event', 'prompt' => '请选择', 'required' => true]); ?>
        </div>

        <div class="form-group">
            <label  for="teamA">队伍一</label>
            <?= Html::dropDownList('sub-class', null, [], ['class' => 'form-control team', 'id' => 'teamA', 'prompt' => '请选择', 'required' => true]); ?>
        </div>

        <div class="form-group">
            <label for="teamB">队伍二</label>
            <?= Html::dropDownList('channel', null, [], ['class' => 'form-control team', 'id' => 'teamB', 'prompt' => '请选择', 'required' => true]); ?>
        </div>

        <button class="btn btn-info event-choose">选择</button>

    </div>

<?php

$teamUrl = Url::to(['ott-event-team/drop-down-list']);


$requestJs=<<<JS
    var prompt = '<option value="">请选择</option>';
    $(document).on('change', '#event', function() {
         var event_id = $(this).val();
         $.getJSON('{$teamUrl}',{event_id:event_id}, function(data) {
              var team = $('.team');
                  team.html('');
                  team.append(prompt);
                  team.html(prompt);
          $.each(data, function(index, value) {
                  var node = '<option value="'+ index +'">' + value + '</option>';
                  team.append(node);
          });
       });  
    });
    
   
JS;

$this->registerJs($requestJs);

?>