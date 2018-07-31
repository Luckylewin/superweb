<?php

/**
 * @var $model \backend\models\LogInterface
 */
$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile('/statics/themes/default-admin/plugins/echarts/echarts.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD]);

$this->title = Yii::t('backend', 'Interface statics');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
            <div class="col-md-12" style="margin-bottom: 50px;margin-top: 40px;">
                <div class="col-md-6 col-md-offset-3">
                    <form action="" id="myform2">
                        <div class="col-md-3">
                            <input type="text" placeholder="月统计" value="<?= Yii::$app->request->get('type') == 'month' ? Yii::$app->request->get('value') : '' ?>"  name="ym" id="test13" class="form-control">
                        </div>
                    </form>


                    <div class="col-md-3">
                            <input type="text" placeholder="日统计" value="<?= Yii::$app->request->get('type') == 'date' ? Yii::$app->request->get('value') : '' ?>"  name="date" id="test12" class="form-control">
                    </div>


                    <?php if(!Yii::$app->request->get('ym')): ?>
    <div class="col-md-2" style="margin-bottom: 30px;">
        <a class="btn btn-default" href=""><?= Yii::t('backend','View Detail'); ?></a>

    </div>
<?php endif; ?>

</div>

</div>

<div class="col-md-11" style="margin: 0 50px auto">
    <div id="main" style="height:400px;border:1px dashed #ccc;margin: 0 auto;margin-bottom: 30px;"></div>
</div>

<div class="col-md-11 center" style="margin: 0 50px auto;margin-bottom: 30px;">
    <div id="program" style="height: 400px;border:1px dashed #ccc;">

    </div>
</div>

</div>


<?php

$title = Yii::t('backend', 'Program viewing ranking');

$js=<<<JS
    function setOption(title,subtitle,data,value)
    {
        option = {
            title: {
                x: 'center',
                text: title,
                subtext: subtitle,
                link: 'http://echarts.baidu.com/doc/example.html'
            },
            tooltip: {
                trigger: 'item'
            },
            toolbox: {
                show: true,
                feature: {
                    dataView: {show: true, readOnly: false},
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            calculable: true,
            grid: {
                borderWidth: 0,
                y: 80,
                y2: 60
            },
            xAxis: [
                {
                    type: 'category',
                    show: false,
                    data: data
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    show: false
                }
            ],
            series: [
                {
                    name: '{$title}',
                    type: 'bar',
                    itemStyle: {
                        normal: {
                            color: function(params) {
                                // build a color map as your need.
                                var colorList = [
                                    '#416ac1','#68c39e','#76b2fc','#46b1e8','#27727B',
                                    '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                                    '#544cd7','#C6E579','#F4E001','#F0805A','#26C0C0',
                                    '#B7504B','#A6E579','#C4E001','#D0805A','#36C0C0',
                                    '#0883fa','#AAE579','#CAE001','#DA805A','#3AC0C0'
                                ];
                                return colorList[params.dataIndex]
                            },
                            label: {
                                show: true,
                                position: 'top',
                                formatter: '{b}'
                            }
                        }
                    },
                    data: value,
                    markPoint: {
                        tooltip: {
                            trigger: 'item',
                            backgroundColor: 'rgba(0,0,0,0)',
                            formatter: function(params){
                                return '<img src="'
                                    + params.data.symbol.replace('image://', '')
                                    + '"/>';
                            }
                        },
                        data: [
                            {xAxis:0, y: 350, name:'Line', symbolSize:20, symbol: 'image://../asset/ico/折线图.png'},
                            {xAxis:1, y: 350, name:'Bar', symbolSize:20, symbol: 'image://../asset/ico/柱状图.png'},
                            {xAxis:2, y: 350, name:'Scatter', symbolSize:20, symbol: 'image://../asset/ico/散点图.png'},
                            {xAxis:3, y: 350, name:'K', symbolSize:20, symbol: 'image://../asset/ico/K线图.png'},
                            {xAxis:4, y: 350, name:'Pie', symbolSize:20, symbol: 'image://../asset/ico/饼状图.png'},
                            {xAxis:5, y: 350, name:'Radar', symbolSize:20, symbol: 'image://../asset/ico/雷达图.png'},
                            {xAxis:6, y: 350, name:'Chord', symbolSize:20, symbol: 'image://../asset/ico/和弦图.png'},
                            {xAxis:7, y: 350, name:'Force', symbolSize:20, symbol: 'image://../asset/ico/力导向图.png'},
                            {xAxis:8, y: 350, name:'Map', symbolSize:20, symbol: 'image://../asset/ico/地图.png'},
                            {xAxis:9, y: 350, name:'Gauge', symbolSize:20, symbol: 'image://../asset/ico/仪表盘.png'},
                            {xAxis:10, y: 350, name:'Funnel', symbolSize:20, symbol: 'image://../asset/ico/漏斗图.png'},
                        ]
                    }
                }
            ]
        };

        return option;
    }
JS;

$this->registerJs($js);

?>

<?php

try {
    $total = $model->total;
    $watch = $model->watch;
    $token = $model->getClientToken;
    $ottlist = $model->getOttNewList;
    $app = $model->getNewApp;
    $order = $model->ottCharge;
    $ott_genre = $model->getCountryList;

    $text = Yii::t('backend', 'Interface call statistics');
    $total_text = Yii::t('backend', 'Total');
    $watch_text = Yii::t('backend', 'Watch');
    $token_text = Yii::t('backend', 'Token');
    $ott_text = Yii::t('backend', 'OttList');
    $ott_genre_text = Yii::t('backend', 'OttGenre');
    $app_text = Yii::t('backend', 'AppUpdate');

    $js =<<<JS
    var total_request = [$total];
    var watch_request = [$watch];
    var token_request = [$token];
    var ottlist_request = [$ottlist];
    var app_request = [$app];
    var order_request = [$order];
    var ottgenre_request = [$ott_genre];
    
    option = {
        title: {
            text: '{$text}'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['{$total_text}','{$watch_text}','{$total_text}','{$ott_text}','{$app_text}','{$ott_genre_text}']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: true,
            data: ['00h','01h','02h','03h','04h','05h','06h','07h','08h','09h','10h','11h','12h','13h','14h','15h','16h','17h','18h','19h','20h','21h','22h','23h']
        },
        yAxis: {
            type: 'value'
        },
        series: [
            // data:[234,21,45,232, 256,562,452,213,2345, 3453,5646,5634,6363, 534,632,4523,4356,234,21,45,232, 256,562,452,213,2345, 3453,5646,5634,6363, 534,632,4523,4356]
            {
                name:'{$total_text}',
                type:'line',
                stack: 'All',
                data : total_request
            },
            {
                name:'{$watch_text}',
                type:'line',
                stack: 'watch',
                data : watch_request
            },
            {
                name:'{$token_text}',
                type:'line',
                stack: 'token',
                data : token_request
            },
            {
                name:'{$ott_text}',
                type:'line',
                stack: 'ott',
                data : ottlist_request
            },
            {
                name:'{$app_text}',
                type:'line',
                stack: 'app',
                data : app_request
            },
            {
                name:'{$ott_genre_text}',
                type:'line',
                stack: 'ottgenre',
                data : ottgenre_request
            }
           
        ]
    };

    var myChart = echarts.init(document.getElementById('main'));
    myChart.setOption(option);
JS;

    $this->registerJs($js);

}catch(\Exception $e) {

}


?>

<?php

try {
    $program = "'" . implode(" ','", array_keys($programLog->server_program ? $programLog->server_program : [])) . "'";
    $program_value = implode(",", array_values($programLog->server_program ? $programLog->server_program : []));

    $title = Yii::t('backend', 'Program viewing ranking');
    $type = Yii::t('backend', 'Server + local resolution');

    $js =<<<JS
    var all_program =       [$program];
    var all_program_value = [$program_value];

    var program = echarts.init(document.getElementById('program'));
    var option = setOption('{$title}','{$type}',all_program,all_program_value);
    program .setOption(option);
JS;

    $this->registerJs($js);
}catch(\Exception $e) {

}

?>

<script>
    laydate.render({
        elem: '#test12'
        ,format: 'yyyy/MM/dd'
        ,max:'<?= date("Y-m-d");?>',
        done: function(value, date){
            var url = '<?= \yii\helpers\Url::to(['log/index','type'=>'date', 'value' => '']) ?>';
            url = url + value;
            window.location.href = url;
        }
    });

    //年月选择器
    laydate.render({
        elem: '#test13'
        ,format: 'yyyy/MM'
        ,type: 'month'
        ,max:'<?= date("Y-m-d");?>',
        done: function(value, date){
            var url = '<?= \yii\helpers\Url::to(['log/index','type'=>'month', 'value' => '']) ?>';
            url = url + value;
            window.location.href = url;
        }
    });

</script>