<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\widgets\ajaxInput\AjaxInputWidget;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\widgets\multilang\MultiLangWidget;

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
                    'template' => '{rename} {update} {delete}',
                    'size' => 'btn-sm',
                    'buttons' => [

                            'rename' => function($url, $model) {
                                 return Html::button('影片分类重命名' , [
                                     'title' => $model->exist_num,
                                     'class' => 'fa fa-edit btn  btn-info rename',
                                     'style' => 'margin:2px;',
                                     'data-toggle' => 'modal',
                                     'data-target' => '#rename-modal',
                                     'data-id'     => $model->id,
                                 ]);
                            }
                    ]
            ],
        ],
    ]); ?>
</div>

<!--重命名Modal-->
<?php
Modal::begin([
    'id' => 'rename-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">重命名到其他分类</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::to(['type-item/rename', 'id' => '']);
$requestJs=<<<JS
     $(document).on('click', '.rename', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}' + id, {'id':id},
                    function (data) {
                        $('.modal-body').css('min-height', '200px').html(data);
                    }
                )
            })
JS;
$this->registerJs($requestJs);
Modal::end();
?>


