<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Iptv Type Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-type-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Iptv Type Item', ['create','type_id' => $type->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'zh_name',
            'sort',

            ['class' => 'common\grid\MyActionColumn'],
        ],
    ]); ?>
</div>
