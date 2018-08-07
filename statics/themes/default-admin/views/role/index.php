<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = \Yii::t('backend','Role');
$this->params['breadcrumbs'][] = \Yii::t('backend','Admin Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <?=$this->render('_tab_menu');?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}{summary}{pager}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('backend', 'Description'),
            ],
            [
                'attribute' => 'createdAt',
                'label' => Yii::t('backend', 'Created At'),
                'value' => function($data) {
                    return date('Y-m-d H:i:s', $data->createdAt);
                }
            ],
            [
                'attribute' => 'updatedAt',
                'label' => Yii::t('backend', 'Updated At'),
                'value' => function($data) {
                    return date('Y-m-d H:i:s', $data->updatedAt);
                }
            ],

            [
                'class' => 'common\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {auth} {delete}',
            ],
        ],
    ]); ?>

</div><!-- index -->
