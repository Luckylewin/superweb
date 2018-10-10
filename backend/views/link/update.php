<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Vodlink */

$vodInfo = $model->vodInfo;
$this->title = 'Update Vodlink:';
$this->params['breadcrumbs'][] = '编辑';
?>

<div class="vodlink-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
