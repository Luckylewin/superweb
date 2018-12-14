<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SubClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Genre List');
$this->params['breadcrumbs'][] = ['label' => $mainClass->name, 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/statics/themes/default-admin/plugins/layer/layer.min.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<style>
    .grid-view td,.grid-view th{
        text-align: center;
        vertical-align:middle!important;
    }
</style>

<div class="sub-class-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('backend', 'Create'), '#', [
                'class' => 'btn btn-success',
                'data-toggle' => 'modal',
                'data-target' => '#create-modal',
                'id' => 'create'
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [ 'class' => 'table table-bordered table-hover' ],
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            [
                "class" => "yii\grid\CheckboxColumn",
                "name" => "id",
            ],

            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'contentOptions' => [
                        'class' => 'ajax-td'
                ],
                'options' => ['style' => 'width:200px;'],
                'format' => 'raw',
                'value' => function($model) {
                    $str =  Html::textInput('name', $model->name, [
                        'class' => 'form-control ajax-update',
                        'field' => 'name',
                        'data-id' => $model->id,
                        'old-value' => $model->name
                    ]);
                    return $str = "<div class='text'>{$model->name}</div>" . "<div class='input' style='display: none'>$str</div>";
                }
            ],

            [
                'attribute' => 'zh_name',
                'contentOptions' => [
                    'class' => 'ajax-td'
                ],
                'options' => ['style' => 'width:200px;'],
                'format' => 'raw',
                'value' => function($model) {
                    $str =  Html::textInput('sort', $model->zh_name, [
                        'class' => 'form-control ajax-update',
                        'field' => 'zh_name',
                        'data-id' => $model->id,
                        'old-value' => $model->zh_name
                    ]);
                    return $str = "<div class='text'>{$model->zh_name}</div>" . "<div class='input' style='display: none'>$str</div>";
                }
            ],

            [
                'attribute' => 'sort',
                'contentOptions' => [ 'class' => 'ajax-td'],
                'options' => ['style' => 'width:70px;'],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::textInput('sort', $model->sort, [
                            'class' => 'form-control ajax-update',
                            'field' => 'sort',
                            'data-id' => $model->id,
                            'old-value' => $model->sort
                    ]);
                }
            ],

            [
                'attribute' => 'use_flag',
                'contentOptions' => ['class' => 'ajax-td'],
                'format' => 'raw',

                'value' => function($model) {
                    return \common\widgets\switchInput\SwitcherInputWidget::widget([
                        'id' => $model->id,
                        'field' => 'use_flag',
                        'url' => Url::to(['sub-class/update','field' => 'use_flag' ,'id' => $model->id]),
                        'defaultCheckedStatus' => $model->use_flag,
                        'successTips' => '操作成功',
                        'errorTips'   => '操作失败'
                    ]);

                }
            ],

            [
                    'header' => Yii::t('backend', 'Operation'),
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{next} &nbsp;&nbsp;| &nbsp;&nbsp;{update} {delete}',
                    'buttons' => [
                        'next' => function($url ,$model) {
                            return Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;', ['ott-channel/index', 'sub-id' => $model->id], [
                                'class' => 'btn btn-success btn-sm'
                            ]);
                        },
                        'update' => function($url, $model) {
                            return \common\widgets\frameButton::widget([
                                    'url' => $url,
                                    'options' => ['class' => 'btn btn-primary btn-sm'],
                                    'content' => '编辑'
                            ]);

                        }
                    ]
            ],

        ],
    ]); ?>


<div>

    <?php if(isset($mainClass)): ?>
        <?php $version = (new \backend\models\Cache())->getCacheVersion($mainClass->name); ?>
        <?= Html::a(Yii::t('backend', 'Generate cache'). "($version)" , '#', [
            'url' => Url::to(['sub-class/generate-cache', 'id' => $mainClass->id]),
            'class' => 'btn btn-success',
            'id' => 'cache-btn',
            'data-toggle' => 'modal',
            'data-target' => '#cache-modal',
        ])  ?>
    <?php endif; ?>

    <?= Html::a(Yii::t('backend', 'Rearrange channel numbers'), ['sub-class/reset-number','main_class_id' => $mainClass->id], ['class' => 'btn btn-primary']) ?>

    <?= Html::a(Yii::t('backend', 'Batch Import'), Url::to(['sub-class/import-via-text', 'mode' => 'keywordChannel']), ['class' => 'btn btn-info'])  ?>

    <?= Html::button(Yii::t('backend', 'Batch Deletion'),['class' => 'gridview btn btn-danger']) ?>

    <?= Html::a(Yii::t('backend', 'Go Back'), Url::to(['main-class/index']), ['class' => 'btn btn-default']) ?>

</div>
</div>

<?php $this->render('index/_ajax',['mainClass' => $mainClass]) ?>