<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VodList */

$this->title = '更新分类';
$this->params['breadcrumbs'][] = ['label' => '点播列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->list_id, 'url' => ['view', 'id' => $model->list_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vod-list-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
