<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ApkDetail */

$this->title = $model->apkName->typeName . ' '. $model->ver;
$this->params['breadcrumbs'][] = ['label' => 'Apk Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                    'attribute' => 'apk_ID',
                    'value' => function($data) {
                        return $data->apkName->typeName;
                    }
            ],
            //'type',
            'ver',
            'md5',
            'url:url',
            'content:ntext',
            'sort',
            'force_update',
        ],
    ]) ?>

</div>

<p>
    <?= Html::a('编辑', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('删除', ['delete', 'id' => $model->ID], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['apk-detail/index', 'id' => $model->apk_ID]), ['class' => 'btn btn-default']) ?>
</p>
