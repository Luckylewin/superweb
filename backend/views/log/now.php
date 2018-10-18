<?php
$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile('https://cdn.bootcss.com/echarts/4.1.0/echarts.common.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD]);

?>

<div class="col-md-12 well">
    <div id="main" style="height:400px;border:1px dashed #ccc;margin-bottom: 30px;"></div>
</div>

<div class="col-md-12 well" style="margin-bottom: 30px;">
    <div id="program" style="height: 400px;border:1px dashed #ccc;">

    </div>
</div>

<?php

// x轴
$axisX = "'" . implode("','", $axisX) . "'";

$total = implode(",", $data['all']);
$watch = implode(",", $data['program']);
$token = implode(",", $data['getClientToken']);


$program_key = "'" . implode(" ','", $program['key']) . "'";
$program_value = implode(",", $program['value']);

$title = Yii::t('backend', 'Interface call statistics');

$total_text = Yii::t('backend', 'Total number');
$watch_text = Yii::t('backend', 'Watch');
$token_text = Yii::t('backend', 'Token');

$titleRanking = Yii::t('backend', 'Program viewing ranking');
$desc = Yii::t('backend', 'Server + local resolution');

$js =<<<JS
    var total_request = [$total];
    var watch_request = [$watch];
    var token_request = [$token];

    option = {
        title: {
            text: '{$title}'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['{$total_text}','{$watch_text}','{$token_text}']
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
            //['00h','01h','02h','03h','04h','05h','06h','07h','08h','09h','10h','11h','12h','13h','14h','15h','16h','17h','18h','19h','20h','21h','22h','23h']
            data: [$axisX]
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
                stack: 'Ott',
                data : watch_request
            },
            {
                name:'{$token_text}',
                type:'line',
                stack: 'token',
                data : token_request
            }
        ]
    };

    var myChart = echarts.init(document.getElementById('main'));
    myChart.setOption(option);

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
                    name: '{$titleRanking}',
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

    var all_program =       [$program_key];
    var all_program_value = [$program_value];

    var program = echarts.init(document.getElementById('program'));
    var option = setOption('{$titleRanking}','{$desc}',all_program,all_program_value);
    program .setOption(option);
JS;

$this->registerJs($js);

?>
