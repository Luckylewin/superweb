<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_id',
            'order_sign',
            'order_status',
            'order_uid',
            'order_total',
            'order_money',
            'order_ispay',
            'order_addtime:datetime',
            'order_paytime:datetime',
            'order_confirmtime:datetime',
            'order_info:ntext',
            'order_paytype',
        ],
    ]) ?>

</div>

<p>
   <?= Html::a(Yii::t('backend', 'Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
</p>
