new Vue({
  el: '#app',
  data() {
    return {
      items:[],
      defaultImg:'this.src="/images/138308922.png"',
      dateSelectedIndex:0,
      scroll: ''
    }
  },

  mounted(){
    // this.checkDivScroolTop();
  },

  created(){
    this.fetch();
  },

  methods: {

    url() {
      return  'http://' + window.location.hostname + ':2052/events?lang=zh_CN';
    },

    fetch() {
      let cacheName = 'NBA-events';
      let cacheDateKey = 'NBA-events-date';
      let storageEvents = localStorage.getItem(cacheName);

      if (localStorage.getItem(cacheDateKey) === this.getTodayDate()) {
        this.items = JSON.parse(storageEvents);

      } else {
        axios.get(this.url())
          .then(response => {
            this.items = response.data.data;
            localStorage.setItem(cacheName,JSON.stringify(this.items));
            localStorage.setItem(cacheDateKey, this.getTodayDate());
          })
          .catch(err => {
            console.log(err)
          });
      }

      this.print(this.items)

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
    },

    getTodayDate() {
      return this.timestampToDate((new Date()).getTime());
    },

    isToday(date) {
      return date === this.getTodayDate()
    },

    time() {
      return parseInt((new Date()).getTime().toString().substring(0,10));
    },

    getDateClass (date,index) {
      return  this.dateSelectedIndex === index ? 'active' : '';
      // return this.isToday(date) ? 'active' : '';
    },

    timestampToDate(obj) {
      let date =  new Date(obj);
      let y = 1900 + date.getYear();
      let m = "0" + (date.getMonth()+1);
      let d = "0" + date.getDate();

      return y + "-" + m.substring(m.length-2,m.length) + "-" + d.substring(d.length - 2,d.length);
    },

    scrollTop(index) {
      let top = index ? document.getElementsByClassName('schedule-block')[index].offsetTop : 0;
      this.dateSelectedIndex = index;

      let scrollDiv = document.getElementsByClassName('scroll-area')[0];

      //绑定事件
      scrollDiv.scrollTop = top;

    },

    checkDivScroolTop(){
      //获取节点
      let scrollDiv = document.getElementsByClassName('scroll-area')[0];

      //绑定事件
      scrollDiv.addEventListener('scroll', function() {
      });
    },

    downloadApp() {
      window.location.href = 'http://www.hdiptv.vip/index.php?r=index%2Fdownload&app=7';
    }
  }
})