<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AppBootPicture */

$this->title = 'Create App Boot Picture';
$this->params['breadcrumbs'][] = ['label' => 'App Boot Pictures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-boot-picture-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
