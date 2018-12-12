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

?>

<div class="play-group-index">
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => "<div class='text-center'>没有找到分组播放地址，请".Html::a('添加分组', \yii\helpers\Url::to(['play-group/create', 'vod_id' => $vod_id]))."</div>",
        'itemView' => '_item',
        'layout' => '{items}'
    ]); ?>
    <p>
        <?= Html::a(Yii::t('backend', 'Create Group'), \yii\helpers\Url::to(['play-group/create', 'vod_id' => Yii::$app->request->get('vod_id')]), ['class' => 'btn btn-success col-md-12']) ?>
    </p>

</div>

