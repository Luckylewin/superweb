<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SubClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类列表';
$this->params['breadcrumbs'][] = ['label' => $mainClass->name, 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-class-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加分类', '#', [
                'class' => 'btn btn-success',
                'data-toggle' => 'modal',
                'data-target' => '#create-modal',
                'id' => 'create'
        ]) ?>

        <?= Html::a('批量导入', Url::to(['sub-class/import-via-text', 'mode' => 'keywordChannel']), [
            'class' => 'btn btn-info'
        ])  ?>

        <?php if(isset($mainClass)): ?>
            <?= Html::a('生成缓存', Url::to(['sub-class/generate-cache', 'id' => $mainClass->id]), [
                'class' => 'btn btn-primary'
            ])  ?>
        <?php endif; ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered table-hover'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'zh_name',
            [
                'attribute' => 'sort',
                'options' => ['style' => 'width:70px;']
            ],
            [
                'attribute' => 'use_flag',
                'value' => function($model) {
                    return $model->getUseText();
                }
            ],
            [
                    'header' => '操作',
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{next} &nbsp;&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;&nbsp;{view} {update} {delete}',
                    'buttons' => [
                        'next' => function($url ,$model) {
                            return Html::a('&nbsp;&nbsp;>>&nbsp;&nbsp;', ['ott-channel/index', 'sub-id' => $model->id], [
                                'class' => 'btn btn-success btn-xs'
                            ]);
                        },

                    ]
            ],

        ],
    ]); ?>

    <?php

    Modal::begin([
        'id' => 'create-modal',
        'header' => '<h4 class="modal-title">创建二级分类</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    Modal::end();

    ?>

    <?php
    $requestUrl = Url::toRoute(['sub-class/create', 'main_id' => $mainClass->id]);
    $js = <<<JS
        $(document).on('click', '#create', function() {
            $.get('{$requestUrl}', {},
                function (data) {
                    $('.modal-body').html(data);
                }
            )
        })
JS;

    $this->registerJs($js);
    ?>

</div>
