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

$this->title = '点播列表';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/statics/themes/default-admin/plugins/layer/layer.min.js', ['depends' => 'yii\web\JqueryAsset'])
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
        <?= Html::a(Yii::t('backend', 'Create'), Url::to(['create','vod_cid' => Yii::$app->request->get('VodSearch')['vod_cid']]), ['class' => 'btn btn-success']) ?>

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


    <?php // $this->render('_search', ['model' => $searchModel]); ?>

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
                  'headerOptions' => ['class' => 'col-md-2'],
                  'filterInputOptions' => [
                    'placeholder' => Yii::t('backend', "Enter movie's name"),
                    'class' => 'form-control'
                  ],
            ],
            //'vod_id',

            [
                    'attribute' => 'vod_type',
                    'headerOptions' => ['class' => 'col-md-1'],
                    'filter' => \backend\models\IptvType::getVodType($search['vod_cid']),
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
                    'attribute' => 'vod_addtime',
                    'headerOptions' => ['class' => 'col-md-1'],
                    'format' => 'date',
                    'value' => function($model) {
                        return $model->vod_addtime;
                    }
            ],
            [
                    'class' => 'common\grid\MyActionColumn',
                'headerOptions' => ['class' => 'col-md-2'],
                    'template' => '{push-home}&nbsp;{banner-create}',
                    'buttons' => [
                        'banner-create' => function($url, $model, $key) {
                            return Html::a('banner', ['banner/create','vod_id' => $model->vod_id], [
                                'class' => 'btn btn-default btn-sm'
                            ]);
                        },
                        'push-home' => function($url, $model, $key) {
                            $text = $model->vod_home ? Yii::t('backend', 'Cancel') : Yii::t('backend', 'Recommend');
                            return Html::a($text, ['vod/push-home','id' => $model->vod_id,'action' => $model->vod_home ? '0' : '1' ], [
                                'class' => 'btn btn-sm ' . ($model->vod_home? 'btn-success' : 'btn-default')
                            ]);
                        },
                    ],

                    'header' => Yii::t('backend', 'Push')
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'headerOptions' => ['class' => 'col-md-4'],
                    'size' => 'btn-sm',
                    'template' => '{link-index} {scheme-setting} {view} {update} {delete}',

                    'buttons' => [

                            'link-index' => function($url, $model) {
                                return Html::a('<i class="glyphicon glyphicon-link"></i> 链接 ', ['play-group/index', 'vod_id' => $model->vod_id], [
                                    'class' => 'btn btn-success btn-sm'
                                ]);
                            },
                            'scheme-setting' => function($url, $model) {
                                return Html::button('<i class="glyphicon glyphicon-cog"></i> 方案号 ', [
                                    'class' => 'btn btn-default btn-sm bind' ,
                                    'data-toggle' => 'modal',
                                    'data-target' => '#setting-scheme-modal',
                                    'data-id' => $model->vod_id,
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
                        $('.modal-body').css('min-height', '200px').html(data);
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
 ?>

