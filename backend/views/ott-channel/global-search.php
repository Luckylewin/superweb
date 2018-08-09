<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\MyAppAsset;


/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */
MyAppAsset::register($this);

$this->title = Yii::t('backend', 'Global Search');
$this->params['breadcrumbs'][] = $this->title;

//引入JS
$this->registerJsFile('/statics/plugins/bootstrap-suggest/bootstrap-suggest.min.js', ['depends' => 'yii\web\JqueryAsset']);

?>

<style>
    td:hover{cursor: pointer}
</style>
<div class="ott-channel-create">
    <h1><?= Html::encode($this->title) ?></h1>


<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('backend', 'search') ?>
    </div>
    <div class="panel-body">
        <div class="col-md-12">

            <?php \yii\bootstrap\ActiveForm::begin([
                    'action' => Url::to(['ott-channel/view']),
                    'method' => 'get'
            ]); ?>

            <div class="col-md-8">
                <div class="input-group">
                    <input name="id" type="text" class="form-control search" autocomplete="off">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <?= Html::submitButton(Yii::t('backend', 'search'), ['class' => 'btn btn-info']); ?>
            </div>

            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>

    </div>
</div>

</div>


<?php

$searchUrl = Url::to(['ott-channel/global-search', 'search' => true, 'value' => '']);
$subClassName = Yii::t('backend', 'genre');
$name = Yii::t('backend', 'Name');
$alias = Yii::t('backend', 'Alias Name');

$js=<<<JS
   /**
   * 淘宝搜索 API 测试
   */
  $(".search").bsSuggest({
    indexId: 2,             //data.value 的第几个数据，作为input输入框的内容
    indexKey: 1,            //data.value 的第几个数据，作为input输入框的内容
    allowNoKeyword: false,  //是否允许无关键字时请求数据。为 false 则无输入时不执行过滤请求
    multiWord: true,        //以分隔符号分割的多关键字支持
    separator: ",",         //多关键字支持时的分隔符，默认为空格
    getDataMethod: "url",   //获取数据的方式，总是从 URL 获取
    showHeader: true,       //显示多个字段的表头
    autoDropup: true,       //自动判断菜单向上展开
    effectiveFieldsAlias:{Id: "序号", Keyword: "关键字"},
    //url: '//suggest.taobao.com/sug?code=utf-8&extras=1&q=', /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
    url: '{$searchUrl}', /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
    jsonp: 'callback',               //如果从 url 获取数据，并且需要跨域，则该参数必须设置
    // url 获取数据时，对数据的处理，作为 fnGetData 的回调函数
    fnProcessData: function(json){
      var index, len, data = {value: []};

      if(! json || ! json.result || ! json.result.length) {
        return false;
      }

      len = json.result.length;

      for (index = 0; index < len; index++) {
        data.value.push({
          "{$subClassName}": json.result[index][0],
          "Id": json.result[index][1],
          "{$name}": json.result[index][2],
          "{$alias}": json.result[index][3]
        });
      }
      //console.log('API: ', data);
      return data;
    }
  }).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
  }).on('onSetSelectValue', function (e, keyword, data) {
    //console.log('onSetSelectValue: ', keyword, data);
    $('.btn').click();
  }).on('onUnsetSelectValue', function () {
    console.log("onUnsetSelectValue");
  });
JS;

$this->registerJs($js);
?>



