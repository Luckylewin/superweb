<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Main Classes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-class-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Main Class', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入', ['sub-class/import-via-text','mode' => 'mainClass'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-hover table-bordered'
        ],
        'columns' => [
            'sort',
            'name',
            'zh_name',
            'description',
            [
                'label' => '缓存版本',
                'value' => function($model) {
                    return (new \backend\models\Cache())->getCacheVersion($model->name);
                }
            ],
            //'icon',
            //'icon_bg',

            [
                'class'=> 'common\grid\MyActionColumn',
                'options' => ['style' => 'width:260px;'],
                'template' => '{next} &nbsp;&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;&nbsp;{view} {update} {delete}',
                'buttons' => [
                        'next' => function($url ,$model) {
                            return Html::a('&nbsp;&nbsp;>>&nbsp;&nbsp;', ['sub-class/index', 'main-id' => $model->id], [
                                'class' => 'btn btn-success btn-xs'
                            ]);
                        }
                ]
            ]


        ],
    ]); ?>

    
</div>
