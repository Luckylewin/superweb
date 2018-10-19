
<div class="container">
    <div class="jumbotron">
        <h1> <i class="fa fa-tv"></i> <?php echo isset(Yii::$app->params['basic']['sitename'])? Yii::t('backend', Yii::$app->params['basic']['sitename'] ): Yii::t('backend', 'Welcome') ?></h1>
        <p><span id="localtime"></span> </p>
        <p>
            <?=  \yii\helpers\Html::a('<i class="fa-bar-chart fa"></i>' . Yii::t('backend', 'Interface statics'), \yii\helpers\Url::to(['log/now']), [
                'class' => 'btn btn-primary btn-lg'
            ]) ?>
        </p>
    </div>
</div>

<div>

    <div class="col-md-3">
        <div class="well" title="php start.php restart -d">
            <i class="fa fa-user" style="font-size: 20px;" ></i> 在线终端:
                <span class="text-success font-bold"><?= $online ?></span> <i>(3小时内)</i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="well" title="php start.php restart -d">
            <i class="fa fa-server" style="font-size: 20px;" ></i> API服务:
            <?php if ($data['apiService']['running']): ?>
                <span class="text-success font-bold">正在运行</span>
            <?php else: ?>
                <span class="text-danger font-bold">停止运行</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="well" title="php yii log/analyse &">
            <i class="fa fa-file-text" style="font-size: 20px;" ></i> 日志服务:
            <?php if ($data['logService']['running']): ?>
                <span class="text-success font-bold">正在运行</span>
            <?php else: ?>
                <span class="text-danger font-bold">停止运行</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="well"  title="php yii queue/listen &">
            <i class="fa fa-arrow-circle-right" style="font-size: 20px;"></i> 队列服务:
            <?php if ($data['queueService']['running']): ?>
                <span class="text-success font-bold">正在运行</span>
            <?php else: ?>
                <span class="text-danger font-bold">停止运行</span>
            <?php endif; ?>
        </div>
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
