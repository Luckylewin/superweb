<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']) ?>

<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'order_sign',
            'order_uid',
            // 'user.username',
            // 'order_total',
             [
                     'attribute' => 'order_money',
                     'headerOptions' => ['class' => 'col-md-1']
             ],


             [
                'attribute' => 'order_addtime',
                'filterInputOptions' => ['class' => 'form-control range'],
                'format' => 'datetime'
             ],

            /*[
                'attribute' => 'order_paytime',
                'filterInputOptions' => ['class' => 'form-control range'],
                'value' => function($model) {
                    if ($model->order_paytime) {
                        return date('Y-m-d H:i', $model->order_paytime);
                    }
                    return '-';
                }
            ],*/

            'order_info:ntext',
            [
                   'attribute' => 'order_paytype',
                   'filter' => \common\models\Order::$payType,

                    'value' => function($model) {
                        return $model->payType;
                    }
            ],

            [
                    'attribute' => 'order_ispay',
                    'filter' => \common\models\Order::getPayStatus(),
                    'format' => 'raw',
                    'value' => function($model) {
                        $text = \common\models\Order::getPayStatus($model->order_ispay);
                        $class = $model->order_ispay ? 'label label-success' : 'label label-default';
                        return Html::tag('span', $text, ['class' => $class]);
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{view} {delete}',
                    'options' => ['style' => 'width:150px;']
            ],
        ],
    ]); ?>
</div>


<?php \common\widgets\Jsblock::begin() ?>
<script>
  lay('.range').each(function(){
    laydate.render({
      elem: this
      ,trigger: 'click'
      ,type: 'date'
      ,range: false
      ,theme: 'grid'
    });
  });
</script>
<?php \common\widgets\Jsblock::end() ?>
