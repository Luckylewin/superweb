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

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('backend','Go Back'), ['karaoke/index'], ['class' => 'btn btn-default']) ?>
    </p>


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

