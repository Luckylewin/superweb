<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Karaoke */

$this->title = '添加卡拉ok曲目';
$this->params['breadcrumbs'][] = ['label' => '卡拉ok列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-name-karaoke-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
