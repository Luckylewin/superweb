<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/css/nba.css" />

    <title></title>
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
            <li class="tabList">
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

        </div>
        <div data-v-4f7b9f4e="" class="section">
            <!---->
            <div data-v-18aa5687="" data-v-4f7b9f4e="" class="cal-wrapper">
                <div data-v-18aa5687="" class="calendar">
                    <div data-v-18aa5687="" data-action="last" data-module="schedule" data-target="btnCalendarLeft"
                         class="arrow left boss">
                                <span data-v-18aa5687="" class="icon-arrows-left-bold pot">
                                </span>
                    </div>
                    <div data-v-18aa5687="" class="info">
                        <ul data-v-18aa5687="" id="calendar" data-module="schedule" data-target="btnCalendar"
                            class="boss" style="width: 4824.6px; left: 0;">

                            <li data-v-18aa5687="" class="" style="width: 114.871px;">
                                        <span data-v-18aa5687="">
                                            12-22
                                        </span>
                                <span data-v-18aa5687="" class="weekday">
                                            周六
                                        </span>
                            </li>
                            <li data-v-18aa5687="" class="" style="width: 114.871px;">
                                        <span data-v-18aa5687="">
                                            12-23
                                        </span>
                                <span data-v-18aa5687="" class="weekday">
                                            周日
                                        </span>
                            </li>
                            <li data-v-18aa5687="" class="" style="width: 114.871px;">
                                        <span data-v-18aa5687="">
                                            12-24
                                        </span>
                                <span data-v-18aa5687="" class="weekday">
                                            周一
                                        </span>
                            </li>
                            <li data-v-18aa5687="" class="active" style="width: 114.871px;">
                                        <span data-v-18aa5687="">
                                            12-25
                                        </span>
                                <span data-v-18aa5687="" class="weekday">
                                            今天
                                        </span>
                            </li>
                            <li data-v-18aa5687="" class="" style="width: 114.871px;">
                                        <span data-v-18aa5687="">
                                            12-26
                                        </span>
                                <span data-v-18aa5687="" class="weekday">
                                            周三
                                        </span>
                            </li>
                            <li data-v-18aa5687="" class="" style="width: 114.871px;">
                                        <span data-v-18aa5687="">
                                            12-27
                                        </span>
                                <span data-v-18aa5687="" class="weekday">
                                            周四
                                        </span>
                            </li>
                            <li data-v-18aa5687="" class="" style="width: 114.871px;">
                                        <span data-v-18aa5687="">
                                            12-28
                                        </span>
                                <span data-v-18aa5687="" class="weekday">
                                            周五
                                        </span>
                            </li>
                        </ul>
                    </div>

                    <div data-v-18aa5687="" data-action="next" data-module="schedule" data-target="btnCalendarRight"
                         class="arrow right boss">
                                <span data-v-18aa5687="" class="icon-arrows-right-bold pot">
                                </span>
                    </div>

                </div>

            </div>
<!--            style="height: 549px;"-->
            <div data-v-f96ad44e="" data-v-4f7b9f4e="" class="game-list">
                <!---->
                <section data-v-f96ad44e="" class="scroll-area ps ps--theme_default ps--active-y"
                         data-ps-id="4fa33b43-5f81-3929-4a6b-aafa0fcc3b9b">
                    <div data-v-f96ad44e="">
                        <div data-v-f96ad44e="" style="min-height: 357px;">

                            <div v-for="item in items">
                                <div data-v-3550de9d="" data-v-f96ad44e="" class="schedule-block">
                                    <div data-v-3550de9d="" class="date">
                                            <span data-v-3550de9d="">
                                            </span>
                                        <span v-text="item.date"></span> 今天
                                    </div>

                                    <div v-for="event in item.event_list">
                                    <a data-v-ef8b519a="" data-v-3550de9d="" href="?mid=100005:2018122400"
                                       target="_blank" class="schedule-item boss game-in">
                                        <div data-v-ef8b519a="" class="date">
                                            <span v-text="event.event_time"></span>
                                            <!---->
                                        </div>
                                        <div data-v-ef8b519a="" class="team team-l t-right">
                                            <!---->

                                            <span data-v-ef8b519a="" v-text="event.event_information.A.name" :title="event.event_information.A.name" class="team-name boss"></span>
                                            <span data-v-ef8b519a="" class="team-score"></span>
                                            <img data-v-4b7e6442="" data-v-ef8b519a="" :src="event.event_information.A.icon"
                                                 :title="event.event_information.A.name" class="team-logo boss" style="width: 36px;" :onerror="defaultImg" />
                                        </div>

                                        <!-- 比分  -->
                                        <div data-v-ef8b519a="" class="score t-center">
                                            0
                                        </div>

                                        <div data-v-ef8b519a="" class="status t-center">
                                            <div data-v-ef8b519a="" class="status-content">
                                                    <span data-v-ef8b519a="" class="game-type" v-text="event.title"></span>
                                                <br data-v-ef8b519a="" />
                                                <span data-v-ef8b519a="" class="game-stage">
                                                     第3节 15:00
                                                </span>
                                            </div>
                                        </div>

                                        <div data-v-ef8b519a="" class="score t-center">
                                            17
                                        </div>
                                        <div data-v-ef8b519a="" class="team">
                                            <img data-v-4b7e6442="" data-v-ef8b519a="" :src="event.event_information.B.icon"
                                                 :title="event.event_information.B.name" class="team-logo boss" style="width: 36px;" :onerror="defaultImg" />
                                            <span data-v-ef8b519a="" v-text="event.event_information.B.name" :title="event.event_information.B.name" class="team-name boss"></span>
                                            <span data-v-ef8b519a="" class="team-score"></span>
                                        </div>

                                        <div data-v-1fb506d0="" data-v-ef8b519a="" class="source t-right game-in">
                                            <div data-v-1fb506d0="" data-module="schedule" data-target="btnLive" class="source-btn normal">
                                                    <span data-v-1fb506d0="" class="live-type-icon ">
                                                        <span data-v-1fb506d0="" class="path1">
                                                        </span>
                                                        <span data-v-1fb506d0="" class="path2">
                                                        </span>
                                                    </span>
                                                <span data-v-1fb506d0="" class="default" v-text="supportedChannel(event)">
                                                        Sport Tv
                                                    </span>
                                                <!---->
                                                <span data-v-1fb506d0="" class="gap">
                                                    </span>
                                                <div data-v-1fb506d0="" class="source-select">
                                                        <span data-v-1fb506d0="" class="arrow">
                                                        </span>
                                                    <span data-v-1fb506d0="" class=" tips option">
                                                            请选择直播平台
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!---->
                                    </a>
                                    </div>
                                </div>
                            </div>

                            <div data-v-3550de9d="" data-v-f96ad44e="" class="schedule-block">
                                <div data-v-3550de9d="" class="date">
                                            <span data-v-3550de9d="">
                                            </span>
                                    12-25 今天
                                </div>

                              <a data-v-ef8b519a="" data-v-3550de9d="" href="?mid=100005:2018122400"
                                   target="_blank" class="schedule-item boss game-in">
                                    <div data-v-ef8b519a="" class="date">
                                        09:15
                                        <!---->
                                    </div>
                                    <div data-v-ef8b519a="" class="team team-l t-right">
                                        <!---->
                                        <span data-v-ef8b519a="" title="野马" class="team-name boss">
                                                    野马
                                                </span>
                                        <span data-v-ef8b519a="" class="team-score">
                                                </span>
                                        <img data-v-4b7e6442="" data-v-ef8b519a="" src="/images/138308499.png"
                                             title="野马" class="team-logo boss" style="width: 36px;" />
                                    </div>
                                    <div data-v-ef8b519a="" class="score t-center">
                                        0
                                    </div>
                                    <div data-v-ef8b519a="" class="status t-center">
                                        <div data-v-ef8b519a="" class="status-content">
                                                    <span data-v-ef8b519a="" class="game-type">
                                                        NFL常规赛
                                                    </span>
                                            <br data-v-ef8b519a="" />
                                            <span data-v-ef8b519a="" class="game-stage">
                                                        第3节 15:00
                                                    </span>
                                        </div>
                                    </div>
                                    <div data-v-ef8b519a="" class="score t-center">
                                        17
                                    </div>
                                    <div data-v-ef8b519a="" class="team">
                                        <img data-v-4b7e6442="" data-v-ef8b519a="" src="/images/138308922.png"
                                             title="突袭者" class="team-logo boss" style="width: 36px;" />
                                        <span data-v-ef8b519a="" title="突袭者" class="team-name boss">
                                                    突袭者
                                                </span>
                                        <span data-v-ef8b519a="" class="team-score">
                                                </span>
                                        <!---->
                                    </div>
                                    <div data-v-1fb506d0="" data-v-ef8b519a="" class="source t-right game-in">
                                        <div data-v-1fb506d0="" data-module="schedule" data-target="btnLive" class="source-btn normal">
                                                    <span data-v-1fb506d0="" class="live-type-icon ">
                                                        <span data-v-1fb506d0="" class="path1">
                                                        </span>
                                                        <span data-v-1fb506d0="" class="path2">
                                                        </span>
                                                    </span>
                                            <span data-v-1fb506d0="" class="default">
                                                        Sport Tv
                                                    </span>
                                            <!---->
                                            <span data-v-1fb506d0="" class="gap">
                                                    </span>
                                            <div data-v-1fb506d0="" class="source-select">
                                                        <span data-v-1fb506d0="" class="arrow">
                                                        </span>
                                                <span data-v-1fb506d0="" class=" tips option">
                                                            请选择直播平台
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!---->
                                </a>


                               <a data-v-ef8b519a="" data-v-3550de9d="" href="?mid=100008:100013862"
                                   target="_blank" class="schedule-item boss game-pre">
                                    <div data-v-ef8b519a="" class="date">
                                        19:35
                                        <!---->
                                    </div>
                                    <div data-v-ef8b519a="" class="team team-l t-right">
                                        <!---->
                                        <span data-v-ef8b519a="" title="北控" class="team-name boss">
                                                    北控
                                                </span>
                                        <span data-v-ef8b519a="" class="team-score">
                                                </span>
                                        <img data-v-4b7e6442="" data-v-ef8b519a="" src="/images/beikong.png"
                                             title="北控" class="team-logo boss" style="width: 36px;" />
                                    </div>
                                    <div data-v-ef8b519a="" class="score t-center">
                                        -
                                    </div>
                                    <div data-v-ef8b519a="" class="status t-center">
                                        <div data-v-ef8b519a="" class="status-content">
                                                    <span data-v-ef8b519a="" class="game-type">
                                                        CBA常规赛
                                                    </span>
                                            <br data-v-ef8b519a="" />
                                            <span data-v-ef8b519a="" class="game-stage">
                                                        未开始
                                                    </span>
                                        </div>
                                    </div>
                                    <div data-v-ef8b519a="" class="score t-center">
                                        -
                                    </div>
                                    <div data-v-ef8b519a="" class="team">
                                        <img data-v-4b7e6442="" data-v-ef8b519a="" src="/images/zhejiang.png"
                                             title="浙江" class="team-logo boss" style="width: 36px;" />
                                        <span data-v-ef8b519a="" title="浙江" class="team-name boss">
                                                    浙江
                                                </span>
                                        <span data-v-ef8b519a="" class="team-score">
                                                </span>
                                        <!---->
                                    </div>
                                    <div data-v-1fb506d0="" data-v-ef8b519a="" class="source t-right game-pre">
                                        <div data-v-1fb506d0="" data-module="schedule" data-target="btnLive" class="source-btn normal">
                                                    <span data-v-1fb506d0="" class="live-type-icon ">
                                                        <span data-v-1fb506d0="" class="path1">
                                                        </span>
                                                        <span data-v-1fb506d0="" class="path2">
                                                        </span>
                                                    </span>
                                            <span data-v-1fb506d0="" class="default">
                                                        Sport Tv
                                                    </span>
                                            <!---->
                                            <span data-v-1fb506d0="" class="gap">
                                                    </span>
                                            <div data-v-1fb506d0="" class="source-select">
                                                        <span data-v-1fb506d0="" class="arrow">
                                                        </span>
                                                <span data-v-1fb506d0="" class=" tips option">
                                                            请选择直播平台
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!---->
                                </a>
                            </div>

                            <div data-v-3550de9d="" data-v-f96ad44e="" class="schedule-block">
                                <div data-v-3550de9d="" class="date">
                                            <span data-v-3550de9d="">
                                            </span>
                                    12-26 星期三
                                </div>
                                <a data-v-ef8b519a="" data-v-3550de9d="" href="?mid=100000:6486"
                                   target="_blank" class="schedule-item boss game-pre">
                                    <div data-v-ef8b519a="" class="date">
                                        01:00

                                    </div>
                                    <div data-v-ef8b519a="" class="team team-l t-right">
                                        <!---->
                                        <span data-v-ef8b519a="" title="雄鹿" url="http://sports.qq.com/kbsweb/teams.htm?cid=100000&amp;tid=15"
                                              class="team-name boss has-team-page">
                                                    雄鹿
                                                </span>
                                        <span data-v-ef8b519a="" class="team-score">
                                                </span>
                                        <img data-v-4b7e6442="" data-v-ef8b519a="" src="/images/15.png" title="雄鹿"
                                             url="http://sports.qq.com/kbsweb/teams.htm?cid=100000&amp;tid=15" class="team-logo boss"
                                             style="width: 36px;" />
                                    </div>
                                    <div data-v-ef8b519a="" class="score t-center">
                                        -
                                    </div>
                                    <div data-v-ef8b519a="" class="status t-center">
                                        <div data-v-ef8b519a="" class="status-content">
                                                    <span data-v-ef8b519a="" class="game-type">
                                                        NBA常规赛
                                                    </span>
                                            <br data-v-ef8b519a="" />
                                            <span data-v-ef8b519a="" class="game-stage">
                                                        未开始
                                                    </span>
                                        </div>
                                    </div>
                                    <div data-v-ef8b519a="" class="score t-center">
                                        -
                                    </div>
                                    <div data-v-ef8b519a="" class="team">
                                        <img data-v-4b7e6442="" data-v-ef8b519a="" src="/images/18.png" title="尼克斯"
                                             url="http://sports.qq.com/kbsweb/teams.htm?cid=100000&amp;tid=18" class="team-logo boss"
                                             style="width: 36px;" />
                                        <span data-v-ef8b519a="" title="尼克斯" url="http://sports.qq.com/kbsweb/teams.htm?cid=100000&amp;tid=18"
                                              class="team-name boss has-team-page">
                                                    尼克斯
                                                </span>
                                        <span data-v-ef8b519a="" class="team-score">
                                                </span>
                                        <!---->
                                    </div>
                                    <div data-v-1fb506d0="" data-v-ef8b519a="" class="source t-right game-pre">
                                        <div data-v-1fb506d0="" data-module="schedule" data-target="btnLive" class="source-btn normal">
                                                    <span data-v-1fb506d0="" class="live-type-icon ">
                                                        <span data-v-1fb506d0="" class="path1">
                                                        </span>
                                                        <span data-v-1fb506d0="" class="path2">
                                                        </span>
                                                    </span>
                                            <span data-v-1fb506d0="" class="default">
                                                        Sport Tv
                                                    </span>
                                            <!---->
                                            <span data-v-1fb506d0="" class="gap">
                                                    </span>
                                            <div data-v-1fb506d0="" class="source-select">
                                                        <span data-v-1fb506d0="" class="arrow">
                                                        </span>
                                                <span data-v-1fb506d0="" class=" tips option">
                                                            请选择直播平台
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!---->
                                </a>

                            </div>
                        </div>
                        <footer data-v-f96ad44e="">
                            <div data-v-f96ad44e="">

                                <a data-v-f96ad44e="" href="javascript:void(0)" target="_blank"
                                   rel="nofollow">
                                    About Sport Tv
                                </a>
                                |

                                <a data-v-f96ad44e="" href="http://www.qq.com/map/" target="_blank" rel="nofollow">
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
                    <div id="ps__scrollbar-x-rail" class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                        <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;">
                        </div>
                    </div>


                    <div id="ps__scrollbar-y-rail" class="ps__scrollbar-y-rail" style="top: 0px; height: 549px; right: 0px;">
                        <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 30px;">
                        </div>
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

<script>
  new Vue({
    el: '#app',

    data() {
      return {
        items:[],
        defaultImg:'this.src="/images/138308922.png"'
      }
    },

    created(){
        this.fetch();
    },

    methods: {
        url() {
            return  'http://' + window.location.hostname + ':12389/events';
        },

        fetch() {
            axios.get(this.url())
              .then(res => {
                    this.items = res.data.data;
                    console.log(this.items);
              })
              .catch(err => {
               // console.log(err)
            });
        },

        print(item){
          console.log(item);
        },

        supportedChannel(event) {
          if (event.channel_list && event.channel_list.length) {
              if (event.channel_list[0].channels)  {
               return event.channel_list[0].channels[0].channel_name
            }
          }

          return '';
        }

    }
  })


</script>
</html>