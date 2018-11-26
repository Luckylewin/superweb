<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\widgets\ajaxInput\AjaxInputWidget;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Iptv Type Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-type-item-index">

    <p>
      <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['iptv-type/index','list_id' => Yii::$app->request->get('list_id')]), ['class' => 'btn btn-default']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function($model, $key, $index, $grid) {
            if ($model->is_show == false) {
                return ['style' => 'background:#eee'];
            }
        },
        'tableOptions' => ['class' => 'table table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'options' => ['class' => 'col-md-1'],
                'format' => 'raw',
                'value' => function($model) {
                    return AjaxInputWidget::widget([
                        'url'   => Url::to(['type-item/update', 'id' => $model->id]),
                        'field' => 'name',
                        'value' => $model->name,
                        'options' => [
                            'class' => 'form-control',
                            'style' => 'width:200px;'
                        ]
                    ]);
                }
            ],

            [
                    'attribute' => 'sort',
                    'options' => ['class' => 'col-md-1'],
                    'format' => 'raw',
                    'value' => function($model) {
                       return AjaxInputWidget::widget([
                           'url'   => Url::to(['type-item/update', 'id' => $model->id]),
                           'field' => 'sort',
                           'value' => $model->sort,
                           'options' => [
                                'class' => 'form-control col-md-1',
                                'style' => 'width:80px;'
                           ]
                       ]);
                    }
            ],
            'exist_num',
            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{multi-language} {update} {delete}',
                    'size' => 'btn-sm',
                    'buttons' => [
                            'multi-language' => function($url, $model) {
                                return Html::a('多语言设置', null, [
                                    'class' => 'fa fa-language btn btn-info language',
                                    'data-target' => '#language-modal',
                                    'data-toggle' => 'modal',
                                    'data-id'     => $model->id
                                ]);
                            }
                    ]
            ],
        ],
    ]); ?>
</div>

<!-- 多语言modal -->
<?php
Modal::begin([
    'id' => 'language-modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="modal-title">多语言设定</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['type-item/multi-language', 'id' => '']);
$requestJs=<<<JS
     $(document).on('click', '.language', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}' + id, {'id':id},
                    function (data) {
                        $('.modal-body').css('min-height', '300px').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs);
Modal::end();
?>