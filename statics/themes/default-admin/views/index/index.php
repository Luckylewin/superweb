<div class="container">
    <div class="jumbotron">
        <h1> <i class="fa fa-tv"></i> <?php echo isset(Yii::$app->params['basic']['sitename'])? Yii::$app->params['basic']['sitename'] : '欢迎登录' ?></h1>
        <p id="localtime"></p>
        <p>

            <?= \yii\helpers\Html::a(Yii::t('backend', 'Interface statics'), \yii\helpers\Url::to(['log/now']), [
                    'class' => 'btn btn-primary btn-lg'
            ]) ?>
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
        if  (weekDay === 0)  weekDay="Sunday";
        if  (weekDay === 1)  weekDay="Monday";
        if  (weekDay === 2)  weekDay="Tuesday";
        if  (weekDay === 3)  weekDay="Wednesday";
        if  (weekDay === 4)  weekDay="Thurday";
        if  (weekDay === 5)  weekDay="Friday";
        if  (weekDay === 6)  weekDay="Saturday ";
        colorfoot="";
        str = colorhead + year + "/" + month + "/" + day + " " + hour + ":" + min + ":" + second + "  " + weekDay + colorfoot;
        return(str);
    }

    function tick(){
        var today;
        today = new Date();
        document.getElementById("localtime").innerHTML =  "Today is " + showLocale(today);
        window.setTimeout("tick()", 1000);
    }
    tick();
</script>

<?php \common\widgets\Jsblock::end() ?>
