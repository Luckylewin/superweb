<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\widgets\ajaxInput\AjaxInputWidget;
use yii\helpers\Url;
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
                'attribute' => 'zh_name',
                'options' => ['class' => 'col-md-1'],
                'format' => 'raw',
                'value' => function($model) {
                    return AjaxInputWidget::widget([
                        'url'   => Url::to(['type-item/update', 'id' => $model->id]),
                        'field' => 'zh_name',
                        'value' => $model->zh_name,
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
                    'template' => '{update} {delete}',
                    'size' => 'btn-sm',

            ],
        ],
    ]); ?>
</div>
