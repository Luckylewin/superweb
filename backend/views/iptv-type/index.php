<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile('/statics/themes/default-admin/plugins/layer/layer.min.js', ['depends' => 'yii\web\JqueryAsset']);

$this->title = 'Iptv Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create','vod_list_id' => $list->list_id], ['class' => 'btn btn-success']) ?>

        <?= Html::a(Yii::t('backend', '同步'), ['sync','vod_list_id' => $list->list_id], ['class' => 'btn btn-info']) ?>

        <?= Html::a(Yii::t('backend', 'Language setting'), Url::to(['iptv-type/set-language', 'id' => Yii::$app->request->get('list_id')]),['class' => 'btn btn-primary '])?>

        <?= Html::a(Yii::t('backend', 'Baidu Translate'), Url::to(['iptv-type/translate', 'id' => Yii::$app->request->get('list_id')]),['class' => 'btn btn-primary '])?>

        <?= Html::a(Yii::t('backend','Go Back'), ['vod-list/index'], ['class' => 'btn btn-default']) ?>

    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'field',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img(\common\components\Func::getAccessUrl($model->image),[
                            'width' => '40'
                    ]);
                }
            ],
            [
                    'label' => Yii::t('backend', 'options'),
                    'format' => 'raw',
                    'options' => ['style' => 'width:55%'],
                    'value' => function($model) {
                        $data = \backend\models\IptvTypeItem::getTypeItems($model->id);
                        $str = '';

                        foreach ($data as $key => $item) {
                            if ($item->exist_num && $item->is_show) {
                                $str .= Html::button($item->name , [
                                    'title' => $item->exist_num,
                                    'class' => 'btn btn-info rename',
                                    'style' => 'margin:2px;',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#rename-modal',
                                    'data-id'     => $item->id,
                                ]);
                            } else {
                                $str .= Html::button($item->name , [
                                    'title' => $item->exist_num,
                                    'class' => 'btn btn-default',
                                    'style' => 'margin:2px;'
                                ]);
                            }
                        }

                        $str .= Html::button(Html::tag('i','', ['class' => 'fa fa-plus']) , [
                            'title'       => '添加新的子搜索项',
                            'class'       => 'btn btn-success create',
                            'style'       => 'margin:2px;',
                            'data-toggle' => 'modal',
                            'data-target' => '#create-modal',
                            'data-id'     =>  $model->id,
                        ]);

                        return $str;
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{sub} {update} {delete}',
                    'buttons' => [
                            'sub' => function($url, $model, $key) {
                                return Html::a(Yii::t('backend', 'List'), Url::to(['type-item/index', 'type_id' => $model->id, 'list_id' => Yii::$app->request->get('list_id')]),[
                                        'class' => 'btn btn-sm btn-info'
                                ]);
                            },

                    ]
            ],
        ],
    ]); ?>
</div>

<!--重命名Modal-->
<?php
Modal::begin([
    'id' => 'rename-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">重命名到其他分类</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['type-item/rename', 'id' => '']);
$requestJs=<<<JS
     $(document).on('click', '.rename', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}' + id, {'id':id},
                    function (data) {
                        $('.modal-body').css('min-height', '200px').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs);
Modal::end();
?>

<!--新增Modal-->
<?php
Modal::begin([
    'id' => 'create-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">添加搜索项</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['type-item/create']);
$requestJs=<<<JS
     $(document).on('click', '.create', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}', {'type_id':id},
                    function (data) {
                        $('.modal-body').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs);
Modal::end();

$requestUrl = \yii\helpers\Url::to(['type-item/create']);
$js =<<<JS
    $(document).on('beforeSubmit', 'form#form-save', function () { 
    var form = $(this); 
    //返回错误的表单信息 
    if (form.find('.has-error').length) 
    { 
        return false; 
    } 
    //表单提交 
    $.ajax({ 
      url  : form.attr('action'), 
      type  : 'post', 
      data  : form.serialize(), 
      success: function (response){ 
        layer.alert(11);
        if(response.status === 'success'){ 
           layer.confirm('添加成功，是否继续添加？', {
                  icon: 1,
                  offset: ['130px',''],
                  btn: ['是','否'] //按钮
                }, function(){
                  $('#iptvtypeitem-name').val('');
                  $('#iptvtypeitem-zh_name').val('');
                  layer.closeAll();
                }, function(){
                   layer.msg('刷新当前页面');
                   setTimeout(function(){
                     window.location.reload();
                   },600);
                });
        } 
      }, 
      error : function (){ 
        alert('系统错误'); 
        return false; 
      } 
    }); 
    return false; 
  }); 
JS;

$this->registerJs($js);

?>