<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\IptvTypeItem */

$this->title = 'Create Iptv Type Item';
$this->params['breadcrumbs'][] = ['label' => 'Iptv Type Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-type-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
