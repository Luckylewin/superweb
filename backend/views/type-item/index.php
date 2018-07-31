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
        <?= Html::a('添加条件列表', ['create','type_id' => $type->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['iptv-type/index','list_id' => Yii::$app->request->get('list_id')]), ['class' => 'btn btn-default']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'zh_name',
            'sort',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{update} {delete}',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>
</div>
