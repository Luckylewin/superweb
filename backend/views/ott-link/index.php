<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OttLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Links';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-link-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ott Link', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'channel_id',
            'link:ntext',
            'source',
            'sort',
            //'use_flag',
            //'format',
            //'script_deal',
            //'definition',
            //'method',
            //'decode',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
