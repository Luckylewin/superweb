<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AppMenu */

$this->title = 'Create App Menu';
$this->params['breadcrumbs'][] = ['label' => 'App Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
