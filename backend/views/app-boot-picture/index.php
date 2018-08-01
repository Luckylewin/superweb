<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\blocks\AppBootPictureBlock;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'App Boot Pictures';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    td{text-align: center!important;vertical-align: middle!important;}
</style>
<div class="app-boot-picture-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create') . Yii::t('backend', 'Start Page Image'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'attribute' => 'boot_pic',
                    'format' => ['image', ['width' => '100px']],
                    'value' => function($model) {
                        return \common\components\Func::getAccessUrl($model->boot_pic,600);
                    }

            ],
            'link',
            'during',
            [
                    'attribute' => 'status',
                    'value' => function(AppBootPictureBlock $model) {
                        return $model->getStatusText($model->status);
                    }
            ],
            //'sort',
            //'created_time:datetime',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' =>  '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
