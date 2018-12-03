<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;
use common\widgets\switchInput\SwitcherInputWidget;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Route Management');
$this->params['breadcrumbs'][] = Yii::t('backend', 'System Setting');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    td{vertical-align: middle!important;}
</style>

<div class="menu-index">

    <?=$this->render('_tab_menu');?>

    <?php ActiveForm::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'tableOptions' => ['class' => 'table table-bordered'],
        'rowOptions' => function($model) {
            if($model['pid'] == 0 ) {
                return [
                    'class' => 'active'
                ];
            }
        },
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sort',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::textInput('sort['.$data['id'].']', $data['sort'], ['class' => 'wd35 form-control','style'=>'width:50px;']);
                },
                'options' => ['style'=>'width:40px;'],
            ],
            //'sort',

            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    return $model['level_string'] . Yii::t('backend', $model['origin_name']);
                }
            ],

            [
                'attribute' => 'url',
                'format' => 'raw',
                'contentOptions' => ['style'=>'max-width:200px;overflow:hidden'],
                'value' => function ($model) {
                    return Html::a($model['url'], null, [
                        'class' =>'btn btn-link',
                        'title' => $model['url']
                    ]);
                }
            ],
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function($model) {
                    $map = ['all' => [
                        'class' => 'btn btn-info btn-xs',
                        'text' => '菜单'
                    ], 'rule' => [
                        'class' => 'btn btn-default btn-xs',
                        'text' => '路由'
                    ]];

                    return Html::a($map[$model['type']]['text'], null, [
                        'class' => $map[$model['type']]['class']
                    ]);

                }
            ],
            [
                'class' => 'common\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{create} {update} {delete}',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-plus"></span> ' . Yii::t('backend', 'Create sub-route') , ['create', 'pid' => $key], [
                            'title' => Yii::t('backend', 'Create sub-route'),
                            'class' => 'btn btn-success btn-sm'
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'delete' => Yii::$app->user->can('menu/delete')
                ]
            ],
        ],
    ]); ?>
    <div class="form-group"><?=Html::submitButton('排序', ['class' => 'btn btn-info']) ?></div>
    <?php ActiveForm::end(); ?>
</div>
