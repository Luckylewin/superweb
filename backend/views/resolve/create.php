<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\IptvUrlResolution */

$this->title = '新增';
$this->params['breadcrumbs'][] = ['label' => '正则表达式列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-url-resolution-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
