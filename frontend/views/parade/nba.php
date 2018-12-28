<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/css/nba.css?v=1227" />
    <title>NBA</title>
</head>

<body class="main">
<div id="app">
    <div data-v-4f7b9f4e="">
        <div data-v-4f7b9f4e="" class="kbs-header color-whitebg">
            <ul class="inline t16 color-black">
                <li class="logo">
                    <a href="javascript:void(0)" target="_blank" data-module="top" data-target="logoKbs"
                       class="boss">
                    </a>
                </li>
                <li class="tabList hidden">
                    <a href="javascript:void(0)" data-module="top" data-target="tabKbs"
                       class="boss boss on">
                        NBA
                    </a>
                    <span class="header-tab-on on"></span>
                </li>
            </ul>
        </div>
        <div data-v-4f7b9f4e="" class="content">
            <div data-v-fc334010="" data-v-4f7b9f4e="" class="nav" style="max-height: 1038px; height: 625px;">
                <div data-v-fc334010="" class="nav-items scroll-gamelist">
                    <div data-v-57afac5a="" data-v-fc334010="" class="items-stable">
                        <a data-v-57afac5a="" href="javascript:void(0)" data-module="hot" data-target="hotEvent" class="stable-nav nav-item checked" >
                            <span data-v-57afac5a="" class="match-name" >
                                广告
                            </span>
                        </a>
                    </div>

                    <div data-v-57afac5a="" data-v-fc334010="" class="items-stable">
                        <a data-v-57afac5a="" href="javascript:void(0)" data-module="hot" data-target="hotEvent" class="stable-nav nav-item checked" >
                            <span data-v-57afac5a="" class="match-name" @click="downloadApp">
                                下载
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div data-v-4f7b9f4e="" class="section">
                <!---->
                <div data-v-18aa5687="" data-v-4f7b9f4e="" class="cal-wrapper">
                    <div data-v-18aa5687="" class="calendar">
                        <div data-v-18aa5687="" data-action="last" data-module="schedule" data-target="btnCalendarLeft"
                             class="arrow left boss">
                            <span data-v-18aa5687="" class="icon-arrows-left-bold pot"></span>
                        </div>
                        <div data-v-18aa5687="" class="info">
                            <ul data-v-18aa5687="" id="calendar" data-module="schedule" data-target="btnCalendar"
                                class="boss" style="width: 4824.6px; left: 0;">
                                <li v-for="(item,index) in items" @click="scrollTop(index)" data-v-18aa5687=""  :class="getDateClass(item.date,index)" style="width: 114.871px;">
                                    <span data-v-18aa5687="" v-text="item.date"></span>
                                    <span data-v-18aa5687="" class="weekday" v-text="isToday(item.date)?'今天' : item.weekday"></span>
                                </li>

                            </ul>
                        </div>

                        <div data-v-18aa5687="" data-action="next" data-module="schedule" data-target="btnCalendarRight"
                             class="arrow right boss">
                            <span data-v-18aa5687="" class="icon-arrows-right-bold pot"></span>

                        </div>

                    </div>

                </div>

                <!--style="height: 549px;"-->
                <div data-v-f96ad44e="" data-v-4f7b9f4e="" class="game-list" style="height: 549px;" >
                    <!---->
                    <section data-v-f96ad44e="" class="scroll-area ps ps--theme_default ps--active-y"
                             data-ps-id="4fa33b43-5f81-3929-4a6b-aafa0fcc3b9b" style="overflow: auto!important;">
                        <div data-v-f96ad44e="">
                            <div data-v-f96ad44e="" style="min-height: 357px;">
                                <div v-for="item in items">
                                    <div data-v-3550de9d="" data-v-f96ad44e="" class="schedule-block">
                                        <div data-v-3550de9d="" class="date">
                                            <span data-v-3550de9d=""></span>
                                            <span v-text="item.date"></span>
                                            <span v-if="isToday(item.date)">今天</span>
                                        </div>

                                        <div v-for="event in item.event_list">
                                            <a data-v-ef8b519a="" data-v-3550de9d="" href="javascript:void(0);"
                                               target="_blank" ref="schedule-item" class="schedule-item boss game-in">
                                                <div data-v-ef8b519a="" class="date">
                                                    <span v-text="event.event_time"></span>
                                                    <!---->
                                                </div>
                                                <div data-v-ef8b519a="" class="team team-l t-right">
                                                    <!---->

                                                    <span data-v-ef8b519a="" v-text="event.event_information.A.name" :title="event.event_information.A.name" class="team-name boss"></span>
                                                    <span data-v-ef8b519a="" class="team-score"></span>
                                                    <img data-v-4b7e6442="" data-v-ef8b519a="" :src="event.event_information.A.icon"
                                                         :title="event.event_information.A.name" class="team-logo boss" style="width: 36px;" />
                                                </div>

                                                <!-- 比分  -->
                                                <div data-v-ef8b519a="" class="score t-center" style="display: none;">
                                                    0
                                                </div>

                                                <div data-v-ef8b519a="" class="status t-center">
                                                    <div data-v-ef8b519a="" class="status-content">
                                                        <span data-v-ef8b519a="" class="game-type" v-text="event.title"></span>
                                                        <br data-v-ef8b519a="" />
                                                        <span data-v-ef8b519a="" class="game-stage hidden">
                                                         第3节 15:00
                                                    </span>
                                                    </div>
                                                </div>

                                                <div data-v-ef8b519a="" class="score t-center" style="display: none;">
                                                    17
                                                </div>
                                                <div data-v-ef8b519a="" class="team">
                                                    <img data-v-4b7e6442="" data-v-ef8b519a="" :src="event.event_information.B.icon"
                                                         :title="event.event_information.B.name" class="team-logo boss" style="width: 36px;"  />
                                                    <span data-v-ef8b519a="" v-text="event.event_information.B.name" :title="event.event_information.B.name" class="team-name boss"></span>
                                                    <span data-v-ef8b519a="" class="team-score"></span>
                                                </div>

                                                <div data-v-1fb506d0="" data-v-ef8b519a="" class="source t-right game-in">
                                                    <!-- source-btn  即将开始的比赛加上这个  -->
                                                    <div data-v-1fb506d0=""  class="normal source-btn">
                                                        <span data-v-1fb506d0="" class="default" v-text="supportedChannel(event)" @click="downloadApp">
                                                            Sport Tv
                                                    </span>
                                                    </div>
                                                </div>
                                                <!---->
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <footer data-v-f96ad44e="">
                                <div data-v-f96ad44e="">

                                    <a data-v-f96ad44e="" href="javascript:void(0)" target="_blank"
                                       rel="nofollow">
                                        About Sport Tv
                                    </a>
                                    |

                                    <a data-v-f96ad44e="" href="#" target="_blank" rel="nofollow">
                                        网站导航
                                    </a>
                                </div>
                                <div data-v-f96ad44e="">
                                    Copyright &copy; 2016 - 2018 Sport Tv. All Rights Reserved
                                </div>
                                <div data-v-f96ad44e="">
                                    <a data-v-f96ad44e="" href="#" target="_blank" rel="nofollow">
                                        Sport Tv
                                    </a>
                                    <a data-v-f96ad44e="" href=""
                                       target="_blank" rel="nofollow">
                                        版权所有
                                    </a>
                                </div>
                            </footer>
                        </div>
                      
                    </section>
                </div>
            </div>
        </div>

        <div data-v-4f7b9f4e="" class="addtional">
        </div>
    </div>
</div>
</body>


<script src="https://cdn.jsdelivr.net/npm/vue@2.5.21/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="/js/nba.js?v=12281334"></script>
</html>

