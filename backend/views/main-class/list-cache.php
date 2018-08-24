<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '缓存列表';
$this->params['breadcrumbs'][] = ['label' => '分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vodlink-index">

    <?php
        try {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'scheme',
                        'format' => 'raw',
                        'label' => Yii::t('backend', 'Scheme Name'),
                        'value' => function($model) {
                            return Html::tag('span',$model['scheme'], [
                               'class' => 'label label-default'
                            ]);
                        }
                    ],

                    [
                        'attribute' => 'key_name',
                        'label' => Yii::t('backend', 'Key Name')
                    ],

                    [
                        'class' => 'common\grid\MyActionColumn',
                        'template' => '{view} {delete}',
                        'buttons' => [

                            'view' => function($url, $model) {
                                return Html::a('查看XML', Url::to(['main-class/view-cache', 'key' => $model['key_name']]), [
                                    'class' => 'btn btn-default btn-xs',
                                    'target' => '_blank'
                                ]);
                            },
                            'delete' => function($url, $model) {
                                return Html::a('删除XML', Url::to(['main-class/delete-cache', 'key' => $model['key_name']]), [
                                    'class' => 'btn btn-warning btn-xs'
                                ]);
                            },
                        ]
                    ],
                ],
            ]);
            } catch (\Exception $e) {

        }
    ?>

</div>

<?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
