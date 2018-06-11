<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Vodlink */

$vodInfo = $model->vodInfo;
$this->title = 'Update Vodlink:';
$this->params['breadcrumbs'][] = ['label' => '视频列表', 'url' => Url::to(['vod/index'])];
$this->params['breadcrumbs'][] = ['label' => $vodInfo->vod_name, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '链接列表', 'url' => Url::to(['link/index', 'vod_id' => $vodInfo->vod_id])];
$this->params['breadcrumbs'][] = '编辑';
?>

<div class="vodlink-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
