<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Karaoke */

$this->title = $model->albumName;
$this->params['breadcrumbs'][] = ['label' => '卡拉ok列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-name-karaoke-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'albumName:ntext',
            [
                'attribute' => 'albumImage',
                'format' => ['image', ['width' => 100]],
                'value' => function($model) {
                    if ($model->source == 'Youtube') {
                        return $model->albumImage;
                    } else {
                        return \common\components\Func::getAccessUrl($model->albumImage);
                    }
                }
            ],
            //'tid',
            'mainActor',
            'directors',
            'tags',
            'info:ntext',
            'area',
            'keywords',
            //'wflag',
            'year',
            //'mod_version',
            //'totalDuration',
            //'flag',
            'hit_count',
            //'voole_id',
            //'price',
            //'is_finish',
            //'yesterday_viewed',
            'source',
            [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'value' => function($model) {
                        if ($model->source == 'Youtube') {
                            return Html::a($model->url, "https://www.youtube.com/watch?v=" . $model->url, [
                                'target' => '_blank'
                            ]);
                        } else {
                            return Html::a($model->url, \common\components\Func::getAccessUrl($model->url), [
                                'target' => '_blank'
                            ]);
                        }
                    }
            ],
            [
                    'attribute' => 'act_img',
                    'value' => function($model) {
                        if ($model->source == 'upload') {
                            return $model->albumImage;
                        }
                        return '';
                    }
            ],
            'download_flag',
            //'utime',
            'updatetime',
        ],
    ]) ?>

</div>

<p>
    <?= Html::a('更新', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('删除', ['delete', 'id' => $model->ID], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => '确定要删除吗?',
            'method' => 'post',
        ],
    ]) ?>
    <?= Html::a('返回', ['karaoke/index'], ['class' => 'btn btn-default']) ?>
</p>
