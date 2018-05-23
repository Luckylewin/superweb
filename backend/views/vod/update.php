<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vod */

$this->title = $model->vod_name;
$this->params['breadcrumbs'][] = ['label' => 'Vods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->vod_name, 'url' => ['view', 'id' => $model->vod_id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="vod-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
