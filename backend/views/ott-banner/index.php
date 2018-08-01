<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Generate cache') . "($version)", ['ott-banner/create-cache'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'desc',
            [
                    'attribute' => 'image',
                    'format' => ['image',['width'=>200]],
                    'value' => function($model) {
                        return \common\components\Func::getAccessUrl($model->image);
                    }
            ],
            [
                'attribute' => 'image_big',
                'format' => ['image',['width'=>200]],
                'value' => function($model) {
                    return \common\components\Func::getAccessUrl($model->image_big);
                }
            ],
            //'sort',
            //'channel_id',
            //'url:url',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>
</div>
