<?php

use yii\helpers\Html;
use yii\grid\GridView;
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
?>
<?php

    $css=<<<CSS
    .is_top{background: #1ab394;color: #fff}
    .current_up {border-color:transparent transparent #00AA88 !important; /*透明 透明  黄*/ } .current_down {border-color: #00AA88 transparent transparent !important; /*透明 透明  黄*/ } .triangle_border_up{width:0; height:0; border-width:0 7px 10px; border-style:solid; border-color:transparent transparent #d2d2d2;/*透明 透明  黄*/ position:absolute; top:0; left:50px; cursor: pointer; } /*下箭头*/ .triangle_border_down{display:block; width:0; height:0; border-width:10px 7px 0; border-style:solid; border-color:#d2d2d2 transparent transparent;/*黄 透明 透明 */ position:absolute; bottom:0; left:50px; cursor: pointer; } .triangle_border_up:hover {border-color:transparent transparent #23527c;/*透明 透明  黄*/ } .triangle_border_down:hover {border-color:#23527c transparent transparent;/*黄 透明 透明 */ }
CSS;
    $this->registerCss($css);
?>

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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table-center table table-striped table-bordered'],
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'summaryOptions' => ['tag' => 'p', 'class' => 'text-right text-muted'],
        'pager' => [
                'class' => 'common\widgets\goPager',
                'firstPageLabel' => Yii::t('backend', 'First Page'),
                'lastPageLabel' => Yii::t('backend', 'Last Page'),
                'go' => true
            ],

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
                          'data-name'   => $model->vod_name
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

            [
                'attribute' => 'sort',

                'format' => 'raw',
                'value' => function($model) {
                    return
                        '<div style="position: relative">' .
                        Html::input('text', 'sort', $model->sort, [
                            'class' => 'form-control sort',
                            'style' => 'width:50px;margin-right:10px;'
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
                    'template' => '{link-index} {stick} {more} {update} {delete} ',

                    'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('查看',null, [
                                    'class' => 'btn btn-info btn-sm btn-show',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#show-modal',
                                    'data-id'     => $model->vod_id
                                ]);
                            },
                            'stick' => function($url, $model) {
                                $topClass = $model->is_top ? 'is_top' : 'btn-default';

                                return Html::a('<i class="fa fa-arrow-up"></i>', Url::to(['vod/stick', 'id' => $model->vod_id]),[
                                    'class' => 'btn btn-sm ' . $topClass,
                                    'title' => '链接列表',
                                ]);
                            },
                            'link-index' => function($url, $model) {
                                return Html::button('<i class="glyphicon glyphicon-link"></i> 链接 ', [
                                    'class' => 'btn btn-success btn-sm frame-open',
                                    'title' => '链接列表',
                                    'data-link' => Url::to(['play-group/index', 'vod_id' => $model->vod_id]),
                                    'data-name' => $model->vod_name,
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

                                return \common\widgets\frameButton::widget([
                                    'url' => $url,
                                    'title' => '编辑',
                                    'content' =>Html::tag('i','', ['class' => 'fa fa-edit']),
                                    'options' => [
                                        'class' => 'btn btn-primary btn-sm',
                                        'title' => '编辑',
                                        'data-link' => $url
                                    ]
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
        ],
    ]); ?>
</div>

<?= Html::a('置顶今日新增',['vod/stick-today','cid' => Yii::$app->request->get('VodSearch')['vod_cid']],['class' => 'btn btn-primary']); ?> &nbsp;

<?= Html::a('取消全部置顶',['vod/cancel-stick-today','cid' => Yii::$app->request->get('VodSearch')['vod_cid']],['class' => 'btn btn-warning']); ?> &nbsp;

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

<?= $this->render('index/_more'); ?>
<?= $this->render('index/_bind'); ?>
<?= $this->render('index/_sort'); ?>
<?= $this->render('index/_detail'); ?>



