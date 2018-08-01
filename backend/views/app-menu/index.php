<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\models\AppMenu;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'App Menus';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    td{
        vertical-align: middle!important;
        text-align: center;
    }
</style>
<div class="app-menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create App Menu', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'attribute' => 'icon',
                    'format' => ['image', ['width'=>'30']],
                    'value' => function($model) {
                        return \common\components\Func::getAccessUrl($model->icon);
                    }
            ],
            'name',
            'zh_name',
            //'icon_hover',
            //'icon_big',
            //'icon_big_hover',
            [
                    'attribute' => 'auth',
                    'value' => function($model) {
                        return Yii::t('backend', AppMenu::$isAuth[$model->auth]);
                    }
            ],
            'restful_url:url',

            //'sort',

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>
</div>
