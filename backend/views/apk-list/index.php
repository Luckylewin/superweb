<?php

use yii\helpers\Html;

use yii\widgets\Pjax;
use yii\grid\GridView;
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

        'columns' => [

            [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'id',

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
                'headerOptions' => ['class' => 'col-md-1']
            ],
            [
                    'attribute' => 'typeName',
                'headerOptions' => ['class' => 'col-md-1']
            ],
            [
                'attribute' => 'type',
                'headerOptions' => ['class' => 'col-md-1']
            ],
            [
                'attribute' => 'class',
                'headerOptions' => ['class' => 'col-md-1']
            ],


            [

                'attribute'=>'sort',
                'filter' => false,
                'format'=>['html'],
                'headerOptions' => ['class' => 'col-md-1']
            ],

            [
                    'attribute' => Yii::t('backend', 'File'),
                'headerOptions' => ['class' => 'col-md-1'],
                    'value' => function($model) {
                        return count($model->version);
                    }
            ],

            //'scheme_id',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'headerOptions' => ['class' => 'col-md-4'],
                    'header' => Yii::t('backend', 'Operation'),
                    'size' => 'btn-sm',
                    'template' => '{child} {set-scheme} {view} {update} {delete}',
                    'buttons' => [
                        'child' => function($url,$model, $key) {
                            return Html::a(Yii::t('backend', 'Version List'), \yii\helpers\Url::to(['apk-detail/index','id' => $model->ID]), [
                                'class' => 'btn btn-default btn-sm'
                            ]);
                        },
                        'set-scheme' => function($url,$model, $key) {
                            if (Yii::$app->user->can('apk-list/set-scheme')) {
                                return \common\widgets\frameButton::widget([
                                        'content' => Yii::t('backend', 'Set Scheme'),
                                        'url' => \yii\helpers\Url::to(['apk-list/set-scheme','id' => $model->ID]),
                                        'icon' => 'fa-book',
                                        'options' => ['class' => 'btn btn-default btn-sm']
                                ]);

                            }
                            return '';
                        },


                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<p>
    <?= Html::a(Yii::t('backend', 'Create APK'), ['create'], ['class' => 'btn btn-success']) ?>
</p>
