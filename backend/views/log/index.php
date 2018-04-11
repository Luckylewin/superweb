<?php
$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile('/statics/themes/default-admin/plugins/echarts/echarts.js', ['depends'=>'yii\web\JqueryAsset', 'position'=>\yii\web\View::POS_HEAD]);

$this->title = '日志统计';
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
                            <a class="btn btn-default" href="">查看详细</a>

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

            <div class="col-md-11 center" style="margin: 0 50px auto;margin-bottom: 30px;">
                <div id="server_program" style="height: 400px;border:1px dashed #ccc;">

                </div>
            </div>

            <div class="col-md-11 center" style="margin: 0 50px auto">
                <div id="local_program" style="height: 400px;border:1px dashed #ccc;">

                </div>
            </div>


            <div class="col-md-12" style="margin-top: 50px;">
                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                活动用户
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= $appLog['total'] ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                有效用户
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['valid_total'])?$appLog['valid_total']:0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                请求总数
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['total_request'])?$appLog['total_request']:0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                用户有效请求总数
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['valid_total_request'])?$appLog['valid_total_request']:0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                token请求
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['token_request'])?$appLog['token_request']:0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                token认证成功
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['token_success'])?$appLog['token_success']:0; ?></h1>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-12">

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                节目请求总数
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($programLog['all_program_sum'])?$programLog['all_program_sum']:0 ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                app市场
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['register_request'])?$appLog['register_request']:0 ?></h1>
                        </div>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                ott列表下载
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['ott_request'])?$appLog['ott_request']:0 ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                预告列表
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['parade_request'])?$appLog['parade_request']:0 ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                服务器时间
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['time_request'])?$appLog['time_request']:0 ?></h1>
                        </div>
                    </div>
                </div>



                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                app升级
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['iptv_request'])?$appLog['iptv_request']:0 ?></h1>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-12">
                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                auth请求
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['auth_request'])?$appLog['auth_request']:0 ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                续费请求
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['renew_request'])?$appLog['renew_request']:0 ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                dvb注册请求
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['register_request'])?$appLog['register_request']:0; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                固件升级
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['firmware_request'])?$appLog['firmware_request']:0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                卡拉OK列表
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['karaokeList_request'])?$appLog['karaokeList_request']:0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                卡拉OK播放
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h1><?= isset($appLog['karaoke_request'])?$appLog['karaoke_request']:0; ?></h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>


<script>
    var total_request = [<?= implode(",", isset($log['total_line'])?$log['total_line']:$log['default'])?>];
    var watch_request = [<?= implode(",", isset($log['watch_line'])?$log['watch_line']:$log['default'])?>];
    var token_request = [<?= implode(",", isset($log['token_line'])?$log['token_line']:$log['default'])?>];
    var local_request = [<?= implode(",", isset($log['local_watch_line'])?$log['local_watch_line']:$log['default'])?>];
    var server_request = [<?= implode(",",isset($log['server_watch_line'])?$log['server_watch_line']:$log['default'])?>];

    option = {
        title: {
            text: '接口调用统计'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['请求总数','节目接口','token接口','本地解析','服务器解析']
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
                name:'请求总数',
                type:'line',
                stack: 'All',
                data : total_request
            },
            {
                name:'节目接口',
                type:'line',
                stack: 'Ott',
                data : watch_request
            },
            {
                name:'token接口',
                type:'line',
                stack: 'token',
                data : token_request
            },
            {
                name:'本地解析',
                type:'line',
                stack: 'local',
                data : local_request
            },
            {
                name:'服务器解析',
                type:'line',
                stack: 'server',
                data : server_request
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
                    name: '节目收看排行',
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

    var all_program =       [<?= "'" . implode(" ','", array_keys($programLog['all_program'])) . "'" ?>];
    var all_program_value = [<?= implode(",", array_values($programLog['all_program'])) ?>];

    var local_program = [<?= "'" . implode(" ','", array_keys($programLog['local_program']?$programLog['local_program']:[])) . "'" ?>];
    var local_program_value = [<?= implode(",", array_values($programLog['local_program']?$programLog['local_program']:[])) ?>];

    var server_program = [<?= "'" . implode(" ','", array_keys($programLog['server_program'])) . "'" ?>];
    var server_program_value = [<?= implode(",", array_values($programLog['server_program'])) ?>];

    var program = echarts.init(document.getElementById('program'));
    var option = setOption('节目收看排行','服务器+本地解析',all_program,all_program_value);
    program .setOption(option);

    var local_program_char = echarts.init(document.getElementById('local_program'));
    var local_option = setOption('本地解析收看排行','本地解析',local_program,local_program_value);
    local_program_char.setOption(local_option);

    var server_program_char = echarts.init(document.getElementById('server_program'));
    var server_option = setOption('服务器解析收看排行','服务器解析',server_program,server_program_value);
    server_program_char.setOption(server_option);

</script>

<script>

</script>



<script>

    laydate.render({
        elem: '#test12'
        ,format: 'yyyy/MM/dd'
        ,max:'<?= date("Y-m-d",strtotime("yesterday"));?>',
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