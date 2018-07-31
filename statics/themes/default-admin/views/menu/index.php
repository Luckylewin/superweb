<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Menu Management');
$this->params['breadcrumbs'][] = Yii::t('backend', 'System Setting');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    td{vertical-align: middle!important;}
</style>

<div class="menu-index">

    <?=$this->render('_tab_menu');?>

    <?php ActiveForm::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'tableOptions' => ['class' => 'table table-bordered'],
        'rowOptions' => function($model) {
            if($model['pid'] == 0 ) {
                return [
                        'class' => 'active'
                ];
            }
        },
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sort',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::textInput('sort['.$data['id'].']', $data['sort'], ['class' => 'wd35 form-control','style'=>'width:50px;']);
                },
                'options' => ['style'=>'width:40px;'],
            ],
            //'sort',
            [
                'attribute' => 'icon_style',
                'format' => 'raw',
                'options' => ['style'=>'width:50px;'],
                'value' => function($data) {
                    if (isset($data['icon_style'])) {
                        return '<i class="fa '. $data['icon_style'].'"><i>';
                    }
                    return '';
                }
            ],

           /* [
                'attribute' => 'id',
                'label' => 'ID',
            ],*/
            //'pid',
            //'name',
            [
                'attribute' => 'name',
                'format' => 'raw',
            ],
            'url:url',

            [
                'attribute' => 'display',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::tag('span', Yii::t('backend', Menu::getDisplayText($data['display'])), ['class' => 'label label-sm '.Menu::getDisplayStyle($data['display'])]);
                }
            ],

            [
                'class' => 'common\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{create} {update} {delete}',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-plus"></span> ' . Yii::t('backend', 'Create submenu') , ['create', 'pid' => $key], [
                            'title' => Yii::t('backend', 'Create submenu'),
                            'class' => 'btn btn-success btn-xs'
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <div class="form-group"><?=Html::submitButton('排序', ['class' => 'btn btn-info']) ?></div>
    <?php ActiveForm::end(); ?>
</div>
