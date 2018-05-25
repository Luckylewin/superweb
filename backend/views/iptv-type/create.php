<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\IptvType */

$this->title = 'Create Iptv Type';
$this->params['breadcrumbs'][] = ['label' => 'Iptv Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
