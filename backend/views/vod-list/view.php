<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VodList */

$this->title = $model->list_id;
$this->params['breadcrumbs'][] = ['label' => 'Vod Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-list-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'list_id',
            //'list_pid',
            //'list_sid',
            'list_name',
            'list_dir',
            //'list_status',
            'list_keywords',
            'list_title',
            'list_description',
            [
                'attribute' => 'list_ispay',
                'value' => function($model) {
                    return \common\models\Vod::$chargeStatus[$model->list_ispay];
                }
            ],
            'list_price',
            'list_trysee',
            //'list_extend:ntext',
        ],
    ]) ?>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->list_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->list_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </p>

</div>
