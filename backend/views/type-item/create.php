<?php
/* @var $this yii\web\View */
/* @var $model backend\models\IptvTypeItem */

$this->title = 'Create Iptv Type Item';
$this->params['breadcrumbs'][] = ['label' => 'Iptv Type Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-type-item-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
