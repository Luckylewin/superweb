<?php

use yii\helpers\Html;
use \common\components\Func;
use \common\models\Vod;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$vod_id = Yii::$app->request->get('vod_id');
$vod = Vod::find()->where(['vod_id' => $vod_id])->with(['list' => function($query) {
    $query->select(['list_id', 'list_name']);
}])->one();

$this->title = Yii::t('backend', 'Play Groups');
$this->params['breadcrumbs'][] = ['url' => Url::to(['vod-list/index']), 'label' => $vod->list->list_name];
$this->params['breadcrumbs'][] = ['url' => Func::getLastPage(), 'label' => $vod->vod_name];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="play-group-index">
    <p>
        <?= Html::a(Yii::t('backend', 'Create Group'), \yii\helpers\Url::to(['play-group/create', 'vod_id' => Yii::$app->request->get('vod_id')]), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend', 'Go Back'), Func::getLastPage(), ['class' => 'btn btn-default']) ?>
    </p>

    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => "<div class='text-center'>没有找到分组播放地址，请".Html::a('添加分组', \yii\helpers\Url::to(['play-group/create', 'vod_id' => $vod_id]))."</div>",
        'itemView' => '_item',
        'layout' => '{items}'
    ]); ?>

</div>

