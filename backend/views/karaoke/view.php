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
            'albumImage:url',
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
            'updatetime',
            //'totalDuration',
            //'flag',
            'hit_count',
            //'voole_id',
            //'price',
            //'is_finish',
            //'yesterday_viewed',
            'utime',
            [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->url, "https://www.youtube.com/watch?v=" . $model->url, [
                            'target' => '_blank'
                        ]);
                    }
            ],
            'act_img',
            'download_flag',
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
