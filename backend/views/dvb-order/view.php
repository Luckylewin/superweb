<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DvbOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dvb Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dvb-order-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'product_name',
            'order_num',
            'order_date',
            'order_count',
            'client_id',
        ],
    ]) ?>

</div>

<p>
    <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php if(Yii::$app->user->can('dvb-order/delete')): ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    <?php endif; ?>
    <?= Html::a('返回', ['dvb-order/index'], ['class' => 'btn btn-default']) ?>
</p>
