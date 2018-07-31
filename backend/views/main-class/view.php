<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MainClass */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Main Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-class-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'zh_name',
            'is_charge',
            'price',
            'description',
            'sort',
            'icon',
            'icon_hover',
        ],
    ]) ?>

</div>


<p>
    <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
</p>