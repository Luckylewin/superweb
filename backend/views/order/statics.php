<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/30
 * Time: 16:00
 */
/* @var $todayNum */
/* @var $todaySum */
/* @var $monthNum */
/* @var $monthSum */
/* @var $totalSum */
/* @var $totalNum */
/* @var $month */
/* @var $monthDetail */

$this->registerJsFile('/statics/themes/default-admin/plugins/echarts/echarts.js');
$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<style>
    .row {
        margin-bottom: 20px;
    }
    
</style>

<div class="row">
    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>今日订单数</b>
            </div>
            <div class="panel-body">
                <h3>
                    <?= $todayNum ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>今日订单金额</b>
            </div>
            <div class="panel-body">
                <h3> <?= $todaySum; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <b><?= str_replace('-','年', $month) . '月' ?> 订单数</b>
            </div>
            <div class="panel-body">
                <h3>
                    <?= $monthNum; ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <b> <?= str_replace('-','年', $month) . '月' ?> 金额</b>
            </div>
            <div class="panel-body">
                <h3>
                    <?= $monthSum; ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>总订单数</b>
            </div>
            <div class="panel-body">
                <h3>
                    <?= $totalNum; ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>总订单金额</b>
            </div>
            <div class="panel-body">
                <h3>
                    <?= $totalSum; ?>
                </h3>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div id="main" class="col-md-10 col-md-offset-1 text-center" style="height: 280px;border: 1px dashed #5bc0de;">
        <?php if(empty($monthDetail)): ?>
            <h1 style="line-height: 250px;color: grey">
               该月没有订单数据
            </h1>
        <?php endif; ?>
    </div>
</div>



<?php \yii\bootstrap\ActiveForm::begin(['id' => 'myform']); ?>
<div class="row text-center">
    <div class="input-group col-xs-4 col-xs-offset-4">
            <input type="text" value="<?= $month ?>" name="month" id="keyword" class="form-control range" placeholder="请选择月份" autocomplete="off">
            <span class="input-group-btn">
                <button class="btn btn-info" id="search_submit" type="submit">查看</button>
            </span>
    </div>
</div>

<div class="col-md-12 text-center">
    <button class="btn btn-info indicate" id="search_submit" data-month="<?= $prev ?>">上一月</button>
    <button class="btn btn-primary indicate" id="search_submit" data-month="<?= $next ?>">下一月</button>
</div>

<?php \yii\bootstrap\ActiveForm::end(); ?>


<?php

if (!empty($monthDetail)) {
    $x = implode("','", array_keys($monthDetail));
    $y = implode(',',array_values(\yii\helpers\ArrayHelper::getColumn($monthDetail, 'num')));
} else {
    $x = $y = '';
}


$js=<<<JS
    
    $('.indicate').click(function() {
        var month = $(this).data('month');
        $('#keyword').val(month);
        $('#myform').submit();
    });
    
    lay('.range').each(function(){
    laydate.render({
      elem: this
      ,trigger: 'click'
      ,type: 'month'
      ,range: false
      ,theme: 'grid'
    });
  });
JS;
$this->registerJs($js);

$js=<<<JS
    if (document.getElementById('main')) {
    var myChart = echarts.init(document.getElementById('main'));
    myChart.title = '坐标轴刻度与标签对齐';

    option = {
        color: ['#3398DB'],
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
                data : ['{$x}'],
                axisTick: {
                    alignWithLabel: true
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
                name:'订单数',
                type:'bar',
                barWidth: '60%',
                data:[{$y}]
            }
        ]
    };
    
    myChart.setOption(option);
  }
JS;


if ($monthDetail) {
    $this->registerJs($js);
}

?>


