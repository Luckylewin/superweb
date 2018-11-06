<?php

/**
 * @var $model \backend\models\form\LogChangedForm
 * @var $statics \backend\models\LogStatics
 */
$this->registerJsFile('https://cdn.bootcss.com/echarts/4.1.0/echarts.common.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD]);

$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD] );

/**@var $date string */
/**@var $time string */
$this->title = '统计图';
$this->params['breadcrumbs'][] = ['label' => '日志统计', 'url' => ['log/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12 text-center">
    <h2><?= $time . '<font color="#1e90ff">'.$title.'</font>' . '统计图' ?></h2>
</div>

<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div id="main" class="col-md-12" style="height: 400px"></div>

<?php $form = \yii\bootstrap\ActiveForm::begin([
    'options' => [
            'class' => 'form-inline',
            'id'    => 'my_form'
    ],
]) ?>

<div class="text-center">
    <?= $form->field($model, 'year')->textInput(['autocomplete' => 'off']); ?>
    <?= $form->field($model, 'month')->textInput(['autocomplete' => 'off']); ?>

</div>

<?php \yii\bootstrap\ActiveForm::end() ?>

<?php
$xAxis = "'".implode("','", array_keys($data))."'";
$yAxis = "'".implode("','", array_values($data))."'";
$js=<<<JS
    // 基于准备好的dom，初始化echarts实例
  var myChart = echarts.init(document.getElementById('main'));

  // 指定图表的配置项和数据
  var data = [
    {name:'"2018-07-05"', value:["2018-07-05 18:00:29", 37]},
    {name:'2018-07-04',  value:["2018-07-04 18:00:29", 36]},
    {name:'2018-07-03',  value:["2018-07-03 18:00:29", 36.5]},
    {name:'2018-07-03',  value:["2018-07-03 12:00:29", 36]},
    {name:'2018-07-02',  value:["2018-07-02 18:00:29", 37.5]},
    {name:'2018-07-01',  value:["2018-07-01 18:00:29", 38]}
];
//时间显示范围
var anchor = [
    {name:'2018-07-01', value:['2018-07-01', 0]},
    {name:'2018-07-31', value:['2018-07-31', 0]}
];

var option = {
    color: ['#3398DB'],
    anchor:anchor,
    tooltip : {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        }
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis : [
      
        {
            type : 'category',
            data : [{$xAxis}],
            
            axisLabel: {
               interval:0,
               rotate:40
            }
            
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'{$title}',
            type:'bar',
            barWidth: '60%',
            data:[{$yAxis}]
        }
    ]
};

  // 使用刚指定的配置项和数据显示图表。
  myChart.setOption(option);
JS;

$this->registerJs($js);
?>


<script>
  laydate.render({
    elem: '#logchangedform-year'
    ,format: 'yyyy'
    ,max:'<?= $model->year;?>'
    ,type: 'year',
    done: function(value, date){
      $('#my_form').submit();
    }
  });

  //年月选择器
  laydate.render({
    elem: '#logchangedform-month'
    ,format: 'MM'
    ,type: 'month'
    ,max:'<?= $model->month;?>',
    done: function(value, date){
      $('#my_form').submit();
    }
  });

</script>