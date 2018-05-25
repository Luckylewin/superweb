<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Iptv Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('添加分类字段', ['create','vod_list_id' => $list->list_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'field',
            [
                    'label' => '选项',
                    'format' => 'raw',
                    'options' => ['style' => 'width:60%'],
                    'value' => function($model) {
                        $data = $model->items;
                        $str = '';

                        foreach ($data as $key => $item) {
                            $str .= Html::button($item->zh_name , [
                                    'title' => $item->name,
                                    'class' => 'btn btn-default',
                                    'style' => 'margin:2px;'
                                ]);
                        }
                        return $str;
                    }
            ],
            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{sub} {view} {update} {delete}',
                    'buttons' => [
                            'sub' => function($url, $model, $key) {
                                return Html::a("条件列表", \yii\helpers\Url::to(['type-item/index', 'type_id' => $model->id]),[
                                        'class' => 'btn btn-sm btn-info'
                                ]);
                            }
                    ]
            ],
        ],
    ]); ?>
</div>

<?= Html::a('返回', ['vod-list/index'], ['class' => 'btn btn-default']) ?>
