<!DOCTYPE html>
<html><head lang="en"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>NBA</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-itunes-app" content="app-id=570608623">
    <meta name="screen-orientation" content="portrait">
    <meta name="x5-orientation" content="portrait">
    <link rel="shortcut icon" href="https://mat1.gtimg.com/sports/kbsweb/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/wap.css?v=122715101">
</head>
<body>
<div id="app">
    <header class="nav nav-shadow header">
        <div class="nav-title">
            <h1>
                <a href="javascript:void(0)">Sport Tv</a>
            </h1>
            <h2><a href="javascript:void(0)" class="nav-title-txt"></a></h2>
        </div>
    </header>

    <div class="" style="margin-top: 43px;">
        <img src="/images/adver.png" width="100%;" alt="">
    </div>

    <div class="gamelist-container" id="gameListContainer">
        <div>
            <ul class="gamelist initialized fallback">
                <div v-for="item in items">
                    <li class="group-info" data-is-date="1" data-date="2018-12-28" data-group="0" data-index="0">
                       <span v-text="item.date"></span> <span v-if="isToday(item.date)">今天</span></li>
                    <li v-for="(event,index) in item.event_list" class="game-item" data-date="item.date" data-group="index" data-index="index">
                        <a href="javascript: void(0)" class="detail-url js-go-game" >

                            <div class="game-info small">
                                <div class="left team-box">
                                    <img :src="event.event_information.A.icon">
                                    <h3 v-text="event.event_information.A.name">灰熊</h3>
                                </div>
                                <div class="right team-box">
                                    <img :src="event.event_information.B.icon">
                                    <h3 v-text="event.event_information.B.name">火箭</h3>
                                </div>
                                <div class="game-status">
                                    <div class="goals hide">122 : 120</div>
                                    <div class="game-desc"><span  v-text="event.title"></span></div>
                                    <div class="game-desc" ><span v-text="event.event_time"></span>
                                        <span class="vip"></span>
                                    </div>
                                    <div class="game-icon end"><i class="icon video"></i><span v-text="supportedChannel(event)"></span></div>
                                </div>
                            </div>
                        </a>
                    </li>
                </div>

            </ul>
        </div>
    </div>
    <div class="rank-container"></div>
</div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/vue@2.5.21/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="/js/nba.js"></script>