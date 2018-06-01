<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SysClient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sys Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'phone',
            'admin_id',
            'client_age',
            'client_address',
            'client_email:email',
            'client_qq',
            'client_engname',
        ],
    ]) ?>

</div>
