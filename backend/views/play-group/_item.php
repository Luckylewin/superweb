<?php
use yii\grid\GridView;
use common\models\Vodlink;
use yii\helpers\Html;
use yii\helpers\Url;
use \common\components\Func;

/**@var $model \backend\models\PlayGroup **/
$group_id = Yii::$app->request->get('group_id', false);
$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => Vodlink::find()->where(['group_id' => $model->id]),
    'sort'  => [
            'defaultOrder' => [
                    'episode' => SORT_DESC
            ]
    ],
    'pagination' => [
        'pageSize' => 10,
    ],
]);
?>



<div class="panel panel-default">
    <div class="panel-heading">
        <span class="text-dark">
            播放来源：<b><?= $model->group_name ?></b>
            <?php if($model->use_flag == false): ?>
            <font color="#dc143c">(不可用)</font>
            <?php endif; ?>
        </span>
        <span style="float: right"><?= Html::a('编辑', Url::to(['play-group/update', 'id' => $model->id])) ?></span>
    </div>
    <div class="panel-body">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
            'tableOptions' => ['class' => 'table table-condensed table-striped'],
            'layout' => '{items}{pager}{summary}',

            'columns' => [
               # ['class' => 'yii\grid\SerialColumn'],
                [
                    "class" => "yii\grid\CheckboxColumn",
                    "name" => "id",
                    'options' => ['style' => 'width:20px;']
                ],

                [
                    "attribute" => "episode",
                    'options' => ['style' => 'width:60px;']
                ],

/*
                [
                    'attribute' => 'pic',
                    'format' => ['image', ['width' => '90']]
                ],*/
                [
                        'attribute' => 'title',
                        'enableSorting' => false,
                ],
                [
                    'enableSorting' => false,
                    'attribute' => 'url',
                    'format' => 'raw',
                    'value' => function($mod) use ($model) {

                        $href = $mod->url;

                        if ($mod->save_type == Vodlink::FILE_LINK) {
                            if (strtolower($model->group_name) == 'youtube' && strpos($mod->url, 'play') !== false) {
                                $href = str_replace('sign=','', $mod->url) . '&noauth=true';
                            }

                        }

                        if ($mod->save_type == Vodlink::FILE_SERVER) {
                            $href = Func::getAccessUrl($mod->url, 1800);
                        }

                        $host = Func::pregSieze('/http:\/\/[^\/]+/', $href);
                        $text =  " " . $host. '...' . mb_substr(str_replace($host, '', $href), -22);

                        return Html::a(Html::tag('i',$text, ['class' => 'fa fa-link']), $href, [
                                'class' => 'btn btn-link',
                                'target' => '_blank',
                                'title' => $mod->url
                        ]);
                    },

                ],
                [
                    'attribute' => 'save_type',
                    'enableSorting' => false,
                    'value' => function($model) {
                        return Vodlink::$saveTypeMap[$model->save_type];
                    }
                ],


                [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{update} {delete}',
                    'header' => Yii::t('backend', 'Operation'),
                    'headerOptions' => [
                        'class' => 'col-md-2'
                    ],
                    'buttons' => [
                        'view' => function($url, $model) {
                            return Html::a(Yii::t('backend', 'View'),Url::to(['link/view','vod_id'=>$model->video_id, 'id' => $model->id]),[
                                'class' => 'btn btn-success btn-xs'
                            ]);
                        },
                        'update' => function($url, $model) {
                            return Html::a(Yii::t('backend', 'Edit'),Url::to(['link/update','vod_id'=>$model->video_id, 'id' => $model->id]),[
                                'class' => 'btn btn-info btn-xs'
                            ]);
                        },
                        'delete' => function($url, $model) {
                            return Html::a(Yii::t('backend', 'Delete'),Url::to(['link/delete','vod_id' => $model->video_id, 'id' => $model->id]),[
                                'class' => 'btn btn-danger btn-xs',
                                'data-confirm' => Yii::t('backend', 'Are you sure?')
                            ]);
                        },

                    ],
                    'visibleButtons' => [
                            'delete' => function(){
                                return Yii::$app->user->can('link/delete');
                            }
                    ]
                ],
            ],
        ]); ?>

    </div>
    <div class="panel-footer text-right">
        <?= \yii\helpers\Html::a('增加链接', \yii\helpers\Url::to(['link/create', 'group_id' => $model->id]), ['class' => 'btn btn-success btn-xs']) ?>
        <?php if(Yii::$app->user->can('play-group/delete')): ?>
        <?= \yii\helpers\Html::a('删除此组', \yii\helpers\Url::to(['play-group/delete', 'id' => $model->id]), ['class' => 'btn btn-danger btn-xs', 'data-confirm' => Yii::t('backend', 'Are you sure?')]) ?>
        <?php endif; ?>
    </div>
</div>


