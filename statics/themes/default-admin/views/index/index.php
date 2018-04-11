<div class="container">
    <div class="jumbotron">
        <h1>欢迎登陆！</h1>
        <p id="localtime"></p>
        <p><a class="btn btn-primary btn-lg" role="button">
                更多</a>
        </p>
    </div>
</div>


<?php \common\widgets\Jsblock::begin() ?>


<script type="text/javascript">
    function showLocale(objD){
        var str,colorhead,colorfoot;
        var year = objD.getYear();
        if(year < 1900) year = year+1900;
        var month = objD.getMonth()+1;
        if(month < 10) month = '0' + month;
        var day = objD.getDate();
        if(day < 10) day = '0' + day;
        var hour = objD.getHours();
        if(hour < 10) hour = '0' + hour;
        var min = objD.getMinutes();
        if(min < 10) min = '0' + min;
        var second = objD.getSeconds();
        if(second < 10) second = '0' + second;
        var weekDay = objD.getDay();
        if  ( weekDay === 0 )  colorhead="";
        if  ( weekDay > 0 && weekDay < 6 )  colorhead="";
        if  ( weekDay === 6 )  colorhead="";
        if  (weekDay === 0)  weekDay="星期日";
        if  (weekDay === 1)  weekDay="星期一";
        if  (weekDay === 2)  weekDay="星期二";
        if  (weekDay === 3)  weekDay="星期三";
        if  (weekDay === 4)  weekDay="星期四";
        if  (weekDay === 5)  weekDay="星期五";
        if  (weekDay === 6)  weekDay="星期六";
        colorfoot="";
        str = colorhead + year + "/" + month + "/" + day + " " + hour + ":" + min + ":" + second + "  " + weekDay + colorfoot;
        return(str);
    }

    function tick(){
        var today;
        today = new Date();
        document.getElementById("localtime").innerHTML =  "今天是 " + showLocale(today);
        window.setTimeout("tick()", 1000);
    }
    tick();
</script>

<?php \common\widgets\Jsblock::end() ?>
