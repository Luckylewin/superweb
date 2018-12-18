<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OttChannel */

?>
<div class="ott-channel-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('backend', 'Main Genre'),
                'value' => function($model) {
                    if ($model->mainClass) {
                        return $model->mainClass->name;
                    }
                }
            ],

            [
                'label' => Yii::t('backend', 'Sub Genre'),
                'value' => function($model) {
                    if ($model->subClass) {
                        return $model->subClass->name;
                    }
                }
            ],
            'name',
            'zh_name',
            'keywords',
            'sort',
            'use_flag',
            'channel_number',
            'image',
            'alias_name',
        ],
    ]) ?>

</div>