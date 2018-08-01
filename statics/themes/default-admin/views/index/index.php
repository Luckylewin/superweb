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


<?php

$mon = Yii::t('backend', 'Monday');
$thu = Yii::t('backend', 'Tuesday');
$wed = Yii::t('backend', 'Wednesday');
$thur = Yii::t('backend', 'Thursday');
$fri = Yii::t('backend', 'Friday');
$sat = Yii::t('backend', 'Saturday');
$sun = Yii::t('backend', 'Sunday');
$text = Yii::t('backend', 'Today is');

$JS=<<<JS
    function showLocale(objD){
        var str,colorHead,colorFoot;
        
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
        if  ( weekDay === 0 )  colorHead="";
        if  ( weekDay > 0 && weekDay < 6 )  colorHead="";
        if  ( weekDay === 6 )  colorHead="";
        if  (weekDay === 0)  weekDay="{$sun}";
        if  (weekDay === 1)  weekDay="{$mon}";
        if  (weekDay === 2)  weekDay="{$thu}";
        if  (weekDay === 3)  weekDay="{$wed}";
        if  (weekDay === 4)  weekDay="{$thu}";
        if  (weekDay === 5)  weekDay="{$fri}";
        if  (weekDay === 6)  weekDay="{$sat}";
        colorFoot="";
        str = colorHead + year + "/" + month + "/" + day + " " + hour + ":" + min + ":" + second + "  " + weekDay + colorFoot;
        return(str);
    }

    function tick(){
        var today;
        today = new Date();
        document.getElementById("localtime").innerHTML =  "{$text} " + showLocale(today);
        window.setTimeout("tick()", 1000);
    }
    tick();
JS;

$this->registerJs($JS, \yii\web\View::POS_END);
?>
