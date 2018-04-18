<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VodList */

$this->title = '添加分类';
$this->params['breadcrumbs'][] = ['label' => 'Vod Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-list-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
