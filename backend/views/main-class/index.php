<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use \common\widgets\frameButton;
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
        <?= frameButton::widget([
                'url' => Url::to(['create']),
                'content' => Yii::t('backend', 'Create'),
                'options' => ['class' => 'btn btn-success']
        ]); ?>&nbsp;&nbsp;|&nbsp;
        <?= Html::a('<i class="fa fa-database">&nbsp;</i>' . Yii::t('backend', 'One-click generate cache'), ['sub-class/batch-generate-cache'], ['class' => 'btn btn-success']) ?>&nbsp;&nbsp;|&nbsp;
        <?= Html::a('<i class="fa fa-file-text">&nbsp;</i>' . Yii::t('backend', 'Batch Import'), ['sub-class/import-via-text','mode' => 'mainClass'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<i class="fa fa-file-text">&nbsp;</i>' . Yii::t('backend', 'Batch Export'), ['main-class/export','mode' => 'mainClass'], ['class' => 'btn btn-info btn-export']) ?>&nbsp;&nbsp;|&nbsp;
        <?= Html::a('<i class="fa fa-file-zip-o">&nbsp;</i>' . Yii::t('backend', "Export Channel's Images"), ['main-class/export-image'], ['class' => 'btn btn-primary btn-export-image']) ?>
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
                    'options' => ['style' => 'width:100px;',],
                    'format' => ['image',['height'=>'45']],
                    'value' => function($model) {
                        if (strpos($model->icon, '/') !== false) {
                            return Func::getAccessUrl($model->icon,600);
                        }
                        return null;
                    },

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
                        $version = (new \backend\models\Cache())->getCacheVersion($model->list_name);
                        if ($version) {
                            return Html::a(Yii::$app->formatter->asRelativeTime($version),Url::to(['main-class/list-cache', 'id' => $model->id]), [
                                'class' => 'btn btn-link'
                            ]);
                        }
                        return '';
                    }
                ],

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
                        'update' => function ($url, $model) {
                            return \common\widgets\frameButton::widget([
                                'content' => '编辑',
                                'url' => $url,
                                'options' => ['class' => 'btn btn-primary btn-sm']
                            ]);
                        },
                        'create-cache' => function($url, $model) {
                            return Html::a(Yii::t('backend', 'Generate cache'), '#', [
                                'url' => Url::to(['sub-class/generate-cache', 'id' => $model->id]),
                                'class' => 'btn btn-success btn-sm create-cache',
                                'id' => 'cache-btn'
                            ]);
                        }
                    ]
                ]

            ],
        ]);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

    ?>

    
</div>

<?php if(strpos(Yii::$app->request->hostInfo, '207.38.90.29') !== false || strpos(Yii::$app->request->hostInfo, 'vitvbox.net') !== false): ?>
    <p>
        <?= Html::a('更新直播数据', \yii\helpers\Url::to(['client/anna-ott']), ['class' => 'btn btn-info']) ?>
    </p>
<?php endif; ?>


<?php $this->render('index/_export'); ?>
<?php $this->render('index/_sort'); ?>
