<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Vod;
use yii\helpers\ArrayHelper;
use \common\models\VodList;
use \yii\bootstrap\Modal;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$cid = Yii::$app->request->get('VodSearch')['vod_cid'];
$vodList = VodList::findOne($cid);

$this->title = '点播列表';
$this->params['breadcrumbs'][] = ['url' => Url::to(['vod-list/index']), 'label' => $vodList->list_name];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/statics/themes/default-admin/plugins/layer/layer.min.js', ['depends' => 'yii\web\JqueryAsset']);
?>
<style>
    .current_up
    {
        border-color:transparent transparent #00AA88 !important; /*透明 透明  黄*/
    }

    .current_down
    {
        border-color: #00AA88 transparent transparent !important; /*透明 透明  黄*/
    }

    .triangle_border_up{
        width:0;
        height:0;
        border-width:0 7px 10px;
        border-style:solid;
        border-color:transparent transparent #d2d2d2;/*透明 透明  黄*/
        position:absolute;
        top:0;
        left:64px;
        cursor: pointer;
    }

    /*下箭头*/
    .triangle_border_down{
        display:block;
        width:0;
        height:0;
        border-width:10px 7px 0;
        border-style:solid;
        border-color:#d2d2d2 transparent transparent;/*黄 透明 透明 */
        position:absolute;
        bottom:0;
        left:64px;
        cursor: pointer;
    }

    .triangle_border_up:hover
    {
        border-color:transparent transparent #23527c;/*透明 透明  黄*/

    }

    .triangle_border_down:hover
    {
        border-color:#23527c transparent transparent;/*黄 透明 透明 */

    }
</style>
<div class="vod-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), Url::to(['create','vod_cid' => isset(Yii::$app->request->get('VodSearch')['vod_cid']) ? Yii::$app->request->get('VodSearch')['vod_cid'] : '1']), ['class' => 'btn btn-success']) ?>

        <?php if(strpos(Yii::$app->request->referrer, 'vod-list') !== false): ?>
            <?= Html::a(Yii::t('backend','Go Back'), null, [
                    'class' => 'btn btn-default',
                    'onclick' => 'history.go(-1)'
            ]) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['vod-list/index'], [
                'class' => 'btn btn-default',
                'onclick' => 'history.go(-1)'
            ]) ?>
        <?php endif; ?>

    </p>


    <?php $search = Yii::$app->request->get('VodSearch'); ?>


    <?php \yii\widgets\Pjax::begin([
            'scrollTo' => true,
    ]) ;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table-center table table-striped table-bordered'],
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'summaryOptions' => ['tag' => 'p', 'class' => 'text-right text-muted'],
        'pager' => ['class' => 'common\widgets\goPager', 'go' => true],

        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',

            ],

            [
                "class" => "yii\grid\CheckboxColumn",
                "name" => "id",
            ],

            [
                  'attribute' => 'vod_name',
                  'format' => 'raw',
                  'value' => function($model) {
                      return Html::a(mb_substr($model->vod_name, 0, 20),null, [
                          'class' => 'btn-show',
                          'data-toggle' => 'modal',
                          'data-target' => '#show-modal',
                          'data-id'     => $model->vod_id,
                      ]);

                  },
                  'headerOptions' => ['class' => 'col-md-3'],
                  'filterInputOptions' => [
                    'placeholder' => Yii::t('backend', "Enter movie's name"),
                    'class' => 'form-control',
                      'style' => 'word-break:break-all'
                  ],
            ],
            //'vod_id',
            [
                'attribute' => 'vod_type',
                'headerOptions' => ['class' => 'col-md-3'],
                'contentOptions' => ['style' => 'width:200px;word-break:break-all'],
                'filter' => \backend\models\IptvType::getTypeItem($search['vod_cid'], 'vod_type'),
                'filterInputOptions' => [
                    'prompt' => Yii::t('backend', 'please choose'),
                    'class' => 'form-control'
                ],
            ],

            [
                'attribute' => 'vod_language',
                'headerOptions' => ['class' => 'col-md-1'],
                'filter' => \backend\models\IptvType::getTypeItem($search['vod_cid'], 'vod_language'),
                'filterInputOptions' => [
                    'prompt' => Yii::t('backend', 'please choose'),
                    'class' => 'form-control'
                ],
            ],

            /* [
                    'attribute' => 'vod_cid',
                    'filter' => ArrayHelper::map(VodList::getAllList(),  'list_id', 'list_name'),
                    'value' => 'list.list_name'
            ],*/


          /*  [
                    'attribute' => 'vod_ispay',
                    'filter' => Vod::$chargeStatus,
                    'value' => function($model) {
                        return Vod::$chargeStatus[$model->vod_ispay];
                    }
            ],
            [
                    'attribute' => 'vod_price',
                    'options' => ['style' => 'width:60px;']
            ],*/
            /* [
                  'attribute' => 'vod_gold',
                  'headerOptions' => ['class' => 'col-xs-1'],
              ],*/
            /*[
                'attribute' => 'vod_hits',
                'options' => ['style' => 'width:100px;']
            ],*/
            /*[
                'attribute' => 'vod_gold',
                'options' => ['style' => 'width:100px;']
            ],*/

            /*[
                    'attribute' => 'vod_stars',
                    'filter' => Vod::$starStatus,
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->star;
                    }
            ],*/

            [
                'attribute' => 'sort',
                'headerOptions' => ['class' => 'col-md-1'],
                'format' => 'raw',
                'value' => function($model) {
                    return
                        '<div style="position: relative">' .
                        Html::input('text', 'sort', $model->sort, [
                            'class' => 'form-control sort',
                            'style' => 'width:62px;'
                    ])
                        .
                        "<div class='triangle_border_up' ></div>"   .
                        "<div class='triangle_border_down' ></div>" .
                        '</div>';
                 }
            ],

            [
                    'label' => '分组',
                    'value' => function($model) {
                        if (is_array($model->groups)) {
                            $str = [];
                            foreach ($model->groups as $group) {
                                $str[] = $group->group_name;
                            }

                            return implode(',', $str);
                        }

                        return '';
                    }
            ],

            [
                    'attribute' => 'vod_addtime',
                    'headerOptions' => ['class' => 'col-md-1'],
                    'filter' => false,
                    'format' => 'date',
                    'value' => function($model) {
                        return $model->vod_addtime;
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'headerOptions' => ['class' => 'col-md-4'],
                    'size' => 'btn-sm',
                    'template' => '{link-index} {more} {update} {delete} ',

                    'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('查看',null, [
                                    'class' => 'btn btn-info btn-sm btn-show',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#show-modal',
                                    'data-id'     => $model->vod_id,
                                    'title' => '查看'
                                ]);
                            },
                            'link-index' => function($url, $model) {
                                return Html::button('<i class="glyphicon glyphicon-link"></i> 链接 ', [
                                    'class' => 'btn btn-success btn-sm frame-open',
                                    'title' => '链接列表',
                                    'data-link' => Url::to(['play-group/index', 'vod_id' => $model->vod_id])
                                ]);
                            },
                            'more' => function($url, $model) {
                                return Html::a(Html::tag('i', '', [
                                        'class' => 'fa fa-cog'
                                ]),null, [
                                    'class' => 'btn btn-info btn-sm btn-more',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#more-modal',
                                    'data-id'     => $model->vod_id,
                                    'data-home' => $model->vod_home,
                                    'data-banner-url' => Url::to(['banner/create','vod_id' => $model->vod_id]),
                                    'data-home-url' => Url::to(['vod/push-home','id' => $model->vod_id,'action' => $model->vod_home ? '0' : '1' ]),
                                    'data-home-text' => $model->vod_home ? Html::tag('i', '', ['class' => 'fa fa-thumbs-up']) : Html::tag('i','', ['class' => 'fa fa-thumbs-o-up']),
                                    'data-home-class' => 'btn btn-lg ' . ($model->vod_home? 'btn-success' : 'btn-default'),
                                    'title' => '更多操作'
                                ]);
                            },
                            'update' => function($url, $model) {
                                return Html::button(Html::tag('i','', ['class' => 'fa fa-edit']), [
                                        'class' => 'btn btn-primary btn-sm frame-open',
                                        'title' => '编辑',
                                        'data-link' => $url
                                ]);
                            },

                            'delete' => function($url, $model) {
                                return Html::a(Html::tag('i','', ['class' => 'fa fa-trash']), $url, [
                                    'class' => 'btn btn-warning btn-sm',
                                    'data-confirm' => Yii::t('yii', Yii::t('backend', 'Are you sure?')),
                                    'data-method' => 'post',
                                    'title' => '删除'
                                ]);
                            }


                    ],

                    'header' => Yii::t('backend', 'Operate')
            ],
            //'vod_title',
            //'vod_ename',
            //'vod_keywords',
            //'vod_type',
            //'vod_actor',
            //'vod_director',
            //'vod_content:ntext',
            //'vod_pic',
            //'vod_pic_bg',
            //'vod_pic_slide',
            //'vod_area',
            //'vod_language',
            //'vod_year',
            //'vod_continu',
            //'vod_total',
            //'vod_isend',
            //'vod_addtime:datetime',
            //'vod_filmtime:datetime',
            //'vod_hits',
            //'vod_hits_day',
            //'vod_hits_week',
            //'vod_hits_month',
            //'vod_stars',
            //'vod_status',
            //'vod_up',
            //'vod_down',

            //'vod_price',
            //'vod_trysee',
            //'vod_play',
            //'vod_server',
            //'vod_url:ntext',
            //'vod_inputer',
            //'vod_reurl',
            //'vod_jumpurl',
            //'vod_letter',
            //'vod_skin',
            //'vod_gold',
            //'vod_golder',
            //'vod_length',
            //'vod_weekday',
            //'vod_series',
            //'vod_copyright',
            //'vod_state',
            //'vod_version',
            //'vod_douban_id',
            //'vod_douban_score',
            //'vod_scenario:ntext',


        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end() ;?>

</div>

<?= Html::a(Yii::t('backend', 'Reset Sort'), \yii\helpers\Url::to(['vod/sort-all', 'vod_cid' => Yii::$app->request->get('VodSearch')['vod_cid']]) ,['class' => 'gridview btn btn-primary']); ?> &nbsp;
<?= Html::button(Yii::t('backend', 'Batch Deletion'),['class' => 'gridview btn btn-danger']); ?>

<?php

$batchDelete = \yii\helpers\Url::to(['vod/batch-delete']);

$requestJs=<<<JS
    $(document).on("click", ".gridview", function () {
                var keys = $("#grid").yiiGridView("getSelectedRows");
                var url = '{$batchDelete}' + '&id=' + keys.join(',');
                window.location.href = url;
            });
JS;

$this->registerJs($requestJs);
?>


<?php

//更多操作
Modal::begin([
    'id' => 'more-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">详情</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

$requestJs=<<<JS
    
     $(document).on('click', '.btn-more', function() {
                var id = $(this).data('id');
                var home_url = $(this).data('home-url');
                var banner_url = $(this).data('banner-url');
                $('#more-modal .modal-body').css('height', '100px')
                $('.bind').data('id', id).attr('data-id', id);
                $('#bannerBtn').attr('href', banner_url);
                $('#homeBtn').attr('href', home_url).attr('class', $(this).data('home-class'));
     });
     
     
JS;

echo "<div class='col-md-10 col-md-offset-1 text-center'>";
echo Html::button('<i class="glyphicon glyphicon-cog"></i> 设置方案号 ', [
    'class' => 'btn btn-default btn-lg bind',
    'data-toggle' => 'modal',
    'data-target' => '#setting-scheme-modal',
    'data-id' => '',

]);

echo "&nbsp;";

echo Html::a(Html::tag('i','Banner图推送', ['class' => 'fa fa-file-picture-o']), '', [
    'class' => 'btn btn-default btn-lg',
    'id' => 'bannerBtn',

]);
echo "&nbsp;";

echo Html::a(Html::tag('i','首页推荐', ['class' => 'fa fa-thumbs-up']), '', [
    'class' => 'btn btn-sm btn-default btn-lg',
    'id' => 'homeBtn'
]);

echo "&nbsp;</div>";

$this->registerJs($requestJs);
Modal::end();


//影片详情
Modal::begin([
    'id' => 'show-modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="modal-title">详情</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['vod/view']);
$requestJs=<<<JS
    
     $(document).on('click', '.btn-show', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}', {'id':id},
                    function (data) {
                        $('.modal-body').css('min-width', '700px').css('min-height', '200px').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs);
Modal::end();


//绑定方案号
Modal::begin([
    'id' => 'setting-scheme-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">设置方案号</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['vod/bind-scheme']);
$requestJs=<<<JS
     $(document).on('click', '.bind', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}', {'id':id},
                    function (data) {
                        $('#setting-scheme-modal .modal-body').css('min-height', '200px').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs);
Modal::end();

?>

<?php

$sortUrl = \yii\helpers\Url::to(['vod/sort','vod_cid' => Yii::$app->request->get('VodSearch')['vod_cid']]);
$success = Yii::t('backend', 'Success');
$error = Yii::t('backend', 'operation failed')
?>

<?php $js=<<<JS
  var Sort = {
     sort_up_handler : function() {
                        var tr = $(this).parent().parent().parent(),
                          id = tr.attr('data-key'),
                          index = tr.index() + 1,
                          pre = index - 1,
                          _this = $(this);
                    
                        var preTr = $("#grid tbody tr:nth-child(" + pre + ")"),
                          preId = preTr.attr('data-key');
                    
                        $(this).addClass('current_up');
                    
                        preTr.insertAfter($("#grid tbody tr:nth-child(" + index + ")"));
                        $.getJSON('{$sortUrl}', {id:id,action:'up',compare_id:preId}, function(e) {
                            if (e.status === 'success') {
                              _this.parent().find('input').val(e.data.sort);
                              layer.msg('{$success}');
                            } else {
                              layer.msg('{$error}');
                            }
                          }
                        );
      },
      sort_down_handler : function() {
                        var tr = $(this).parent().parent().parent(),
                            id = tr.attr('data-key'),
                            index = tr.index() + 1,
                            next = index + 1,
                            _this = $(this);
                    
                        var nextTr = $("#grid tbody tr:nth-child(" + next + ")"),
                            nextId = nextTr.attr('data-key');
                    
                        $(this).addClass('current_down');
                    
                        $("#grid tbody tr:nth-child(" + index + ")").insertAfter($("#grid tbody tr:nth-child(" + next + ")"));
                        $.getJSON('{$sortUrl}', {id:id,action:'down',compare_id:nextId}, function(e) {
                            if(e.status === 'success') {
                              _this.parent().find('input').val(e.data.sort);
                              layer.msg('{$success}');
                            } else {
                              layer.msg('{$error}');
                            }
                          }
                        );
      },
      change_handler : function() {
                        var tr = $(this).parent().parent().parent(),
                            sort = $(this).val(),
                            id = tr.attr('data-key');
                    
                        $.getJSON("{$sortUrl}", {id:id,sort:sort,action:'appoint',compare_id:null}, function(e) {
                            if(e.status === 'success') {
                              layer.msg('{$success}');
                            } else {
                              layer.msg('{$error}');
                            }
                          }
                        );   
      }
  }
  
  $(document).on('load pjax:success', function() {
     $('.triangle_border_up').click(Sort.sort_up_handler)
     $('.triangle_border_down').click(Sort.sort_down_handler);
     $('.sort').change(Sort.change_handler);
  }).ready(function() {
     $('.triangle_border_up').click(Sort.sort_up_handler);
     $('.triangle_border_down').click(Sort.sort_down_handler);
     $('.sort').change(Sort.change_handler);
  });

JS;

$this->registerJs($js, \yii\web\View::POS_END);


$js=<<<JS
   
    lay('.range').each(function(){
    laydate.render({
      elem: this
      ,trigger: 'click'
      ,type: 'date'
      ,range: false
      ,theme: 'grid'
    });
  });

  
$(document).on('click', '.frame-open', function() {
  var url = $(this).data('link');
     
      layer.open({
          type: 2,
          area: ['1120px', '600px'],
          fixed: true, //不固定
          maxmin: true,
          content: url
      });
       
      return false;
});


JS;



$this->registerJs($js);


?>

