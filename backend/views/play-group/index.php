<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Play Groups';
$this->params['breadcrumbs'][] = $this->title;
$vod_id = Yii::$app->request->get('vod_id');
$vod = \common\models\Vod::findOne($vod_id);
?>

<div class="play-group-index">
    <p>

        <?= Html::a(Yii::t('backend', 'Create Group'), \yii\helpers\Url::to(['play-group/create', 'vod_id' => Yii::$app->request->get('vod_id')]), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend', 'Go Back'), \yii\helpers\Url::to(['vod/index', 'VodSearch[vod_cid]' => $vod->vod_cid]), ['class' => 'btn btn-default']) ?>

    </p>

    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => "<div class='text-center'>没有找到分组播放地址，请".Html::a('添加分组', \yii\helpers\Url::to(['play-group/create', 'vod_id' => $vod_id]))."</div>",
        'itemView' => '_item',
        'layout' => '{items}'
    ]); ?>

</div>

