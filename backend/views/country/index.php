<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sys Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-country-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sys Country', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',

            'code',
            [
                    'attribute' => 'icon',
                    'format' => ['image', ['width'=>'30']],
                    'value' => function($model) {
                        return \common\components\Func::getAccessUrl($model->icon);
                    }
            ],
            'name',
            'zh_name',
            //'icon_big',

            [
                'class' => 'common\grid\MyActionColumn',
                'size' => 'btn-sm',
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>
</div>
