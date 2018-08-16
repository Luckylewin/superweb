<?php

use yii\helpers\Html;

use yii\widgets\Pjax;
use kartik\grid\GridView;
use \common\oss\Aliyunoss;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ApkListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'APK List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-list-index">


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view',"style"=>"overflow:auto", "id" => "grid"],
        'export' => false,
        'columns' => [

            [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'id',
                    'options' => [
                        'style' => 'width:40px;'
                    ]
            ],

            [
                'class' => 'yii\grid\SerialColumn',
                'options' => [
                    'style' => 'width:50px;'
                ]
            ],

            [
                'attribute' => 'img',
                'format' => ['image',['height'=>'30']],
                'value' => function($data) {
                    if (!empty($data->img)) {
                        if (strpos($data->img, '/') === 0) {
                            return \common\components\Func::getAccessUrl($data->img, 300);
                        } else if (strpos($data->img, 'http') !== false) {
                            return $data->img;
                        } else {
                            return Aliyunoss::getDownloadUrl($data->img);
                        }
                    }
                    return '<i class="fa fa-android" style="font-size: 20px;color:green;text-align: center;text-indent: 7px;"><i>';
                },

                'options' => [
                        'style' => 'width:30px;'
                ]
            ],
            [
                    'attribute' => 'typeName',
                    'options' => [
                        'style' => 'width:130px;'
                    ]
            ],
            [
                'attribute' => 'type',
                'options' => [
                    'style' => 'width:130px;'
                ]
            ],
            [
                'attribute' => 'class',
                'options' => [
                    'style' => 'width:130px;'
                ]
            ],


            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'sort',
                'editableOptions'=>[
                    'header'=> Yii::t('backend', 'Sort'),
                    'inputType'=>\kartik\editable\Editable::INPUT_SPIN,
                    'options'=>['pluginOptions'=>['min'=>0, 'max'=>5000]],
                    'formOptions' => [
                            'action' => ['/apk-list/editsort']
                    ],
                    'submitButton' => [
                            'icon' => '<i class="glyphicon glyphicon-saved"></i>',
                            'class' => 'btn btn-sm btn-default'
                    ]
                ],
                'hAlign'=>'center',
                'vAlign'=>'middle',
                'width'=>'100px',
                'format'=>['html'],
            ],

            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('_expand-row-details', ['model' => $model]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true,
                'mergeHeader' => false,
                'expandIcon' => '<i class="fa fa-android"></i>'
            ],

            //'scheme_id',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'header' => Yii::t('backend', 'Operation'),
                    'size' => 'btn-sm',
                    'template' => '{child} {set-scheme} {view} {update} {delete}',
                    'buttons' => [
                        'child' => function($url,$model, $key) {
                            return Html::a(Yii::t('backend', 'Release Version'), \yii\helpers\Url::to(['apk-detail/create','id' => $model->ID]), [
                                'class' => 'btn btn-default btn-sm'
                            ]);
                        },
                        'set-scheme' => function($url,$model, $key) {
                            if (Yii::$app->user->can('apk-list/set-scheme')) {
                                return Html::a(Yii::t('backend', 'Set Scheme'), \yii\helpers\Url::to(['apk-list/set-scheme','id' => $model->ID]), [
                                    'class' => 'btn btn-default btn-sm'
                                ]);
                            }
                            return '';
                        }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<p>
    <?= Html::a(Yii::t('backend', 'Create APK'), ['create'], ['class' => 'btn btn-success']) ?>
</p>
