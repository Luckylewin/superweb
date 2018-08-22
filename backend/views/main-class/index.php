<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use \yii\bootstrap\Modal;
use \common\components\Func;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Main Classes';
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
    .grid-view th ,.grid-view td {
        text-align: center;
        vertical-align: middle !important;
    }

    .label-red td:nth-child(3){background-color: #dca7a7!important;color: white}
</style>

<div class="main-class-index">
    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>

        &nbsp;&nbsp;

        <?= Html::a(Yii::t('backend', 'Export Image'), ['main-class/export-image'], ['class' => 'btn btn-primary btn-export-image']) ?>
        <?= Html::a(Yii::t('backend', 'Batch Import'), ['sub-class/import-via-text','mode' => 'mainClass'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('backend', 'Batch Export'), ['main-class/export','mode' => 'mainClass'], ['class' => 'btn btn-info btn-export']) ?>
    </p>

    <?php

    try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover table-bordered'],
            "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
            'rowOptions' => function($model, $key, $index, $grid) {
                return $model->use_flag == 0 ? ['class' => 'label-red'] : ['class' => 'label-green'];
            },
            'columns' => [

                ['class' => 'yii\grid\SerialColumn'],

                [
                    "class" => "yii\grid\CheckboxColumn",
                    "name" => "id",
                ],

                [
                    'attribute' => 'icon',
                    'options' => ['style' => 'width:100px;'],
                    'format' => ['image',['height'=>'45']],
                    'value' => function($model) {
                        if (strpos($model->icon, '/') !== false) {
                            return Func::getAccessUrl($model->icon,600);
                        }
                        return null;
                    }
                ],
                'name',
                'zh_name',

                [
                    'attribute' => 'sort',
                    'options' => ['style' => 'width:70px;'],
                    'format' => 'raw',
                    'value' => function($model) {
                        return \yii\bootstrap\Html::textInput('sort', $model->sort, [
                            'class' => 'form-control change-sort',
                            'data-id' => $model->id,
                            'old-value' => $model->sort
                        ]);
                    }
                ],
                [
                    'label' => Yii::t('backend', 'List version'),
                    'format' => 'raw',
                    'value' => function($model) {
                        $version = (new \backend\models\Cache())->getCacheVersion($model->name);
                        if ($version) {
                            return Html::a(Yii::$app->formatter->asRelativeTime($version),Url::to(['main-class/list-cache', 'id' => $model->id]), [
                                'class' => 'btn btn-link'
                            ]);
                        }
                        return '';
                    }
                ],
                //'icon',
                //'icon_bg',

                [
                    'class'=> 'common\grid\MyActionColumn',
                    'options' => ['style' => 'width:360px;'],
                    'size' => 'btn-sm',
                    'template' => '{next}&nbsp;&nbsp;| &nbsp;&nbsp;{create-cache} {view} {update} {delete}',
                    'buttons' => [
                        'next' => function($url ,$model) {
                            return Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;', ['sub-class/index', 'main-id' => $model->id], [
                                'class' => 'btn btn-success btn-sm'
                            ]);
                        },
                        'create-cache' => function($url, $model) {
                            return Html::a(Yii::t('backend', 'Generate cache'), '#', [
                                'url' => Url::to(['sub-class/generate-cache', 'id' => $model->id]),
                                'class' => 'btn btn-success btn-sm create-cache',
                                'id' => 'cache-btn',
                                'data-toggle' => 'modal',
                                'data-target' => '#operate-modal',
                            ]);
                        }
                    ]
                ]

            ],
        ]);
    } catch (\Exception $e) {

    }

    ?>

    
</div>

<?php

Modal::begin([
    'id' => 'operate-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">操作提示</h4>',
    'footer' => '',
]);
echo "<h4><i class='fa fa-spinner fa-pulse'> </i> 生成缓存中</h4>";
Modal::end();

Modal::begin([
    'id' => 'download-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">操作提示</h4>',
    'footer' => '',
]);
echo "<h4><i class='fa fa-spinner fa-pulse'> </i> 请勿关闭,完成将提示下载</h4>";
Modal::end();

?>


<?php

$updateUrl = Url::to(['main-class/update', 'field' => 'sort', 'id'=>'']);
$csrfToken = Yii::$app->request->csrfToken;

$requestJs=<<<JS
    $('.create-cache').click(function(){
        var url = $(this).attr('url');
        setTimeout(function(){
            window.location.href = url;
        },200);
    });
    $('.change-sort').blur(function(){
        var newValue = $(this).val();
        var oldValue = $(this).attr('value');
        
        var id = $(this).attr('data-id');
        var url = '{$updateUrl}' + id;
       
        if (newValue === oldValue) return false;
        
        $.post(url, {sort:newValue,_csrf:'{$csrfToken}'}, function(data){
              window.location.reload();
        })
    });

JS;

$this->registerJs($requestJs);

?>

<?php

$exportUrl = Url::to(['main-class/export', 'id' => '']);
$exportImageUrl = Url::to(['main-class/export-image', 'main_class_id' => '']);
$queryIsDoneUrl = Url::to(['main-class/query-task', 'queue_id' => '']);

$js =<<<JS
    $(document).on('click', '.btn-export', function () {
      var keys = $("#grid").yiiGridView("getSelectedRows");
      window.location.href = '{$exportUrl}' + keys;
      return false;
    });
    
    $(document).on('click', '.btn-export-image', function () {
      $(this).attr('disabled', true);
      
      var keys = $("#grid").yiiGridView("getSelectedRows"),
          url = '{$exportImageUrl}' + keys,
          queryUrl = '{$queryIsDoneUrl}',
          _this = $(this);
      
      $.getJSON(url, function (e) {
            if (e.status && e.queue_id) {
                   queryUrl += e.queue_id;
                   $('#download-modal').modal();
        
                   var timer = setInterval(function() {
                      $.getJSON(queryUrl, function(e) {
                           if (e.status) {
                              clearInterval(timer);
                              $('#download-modal').hide(); 
                              window.location.href = e.url;
                           } 
                      }); 
                   }, 10000)
              }  else {
                 clearInterval(timer);
                 alert('请选择分类');
                 _this.removeAttr('disabled');
              }
    });
      
       
      
      return false;
  });
    

JS;


$this->registerJs($js);

?>
