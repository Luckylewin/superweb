<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\widgets\Jsblock;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\search\OttAccessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Accesses';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/statics/themes/default-admin/plugins/laydate/laydate.js', ['depends' => 'yii\web\JqueryAsset']);

?>

<div class="ott-access-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'attribute' => 'mac',
                    'headerOptions' => [
                        'class' => 'col-md-1'
                    ]
            ],
            [
                    'attribute' => 'genre',
                    'filter' => \common\models\MainClass::getAllListName(),
                    'headerOptions' => [
                            'class' => 'col-md-1'
                    ]
            ],
            [
                'attribute' => 'is_valid',
                'filter' => ['否','是'],

                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-1'
                ],
                'value' => function($model) {
                    $text = $model->is_valid ? Yii::t('backend', 'Yes') : Yii::t('backend', 'No');
                    $class = $model->is_valid ? ['class' => 'label label-success'] : ['class' => 'label label-default'];

                    return Html::tag('span', $text, $class);
                }
            ],

            [
                    'attribute' => 'deny_msg',
                    'filter' => false,
                    'headerOptions' => [
                        'class' => 'col-md-2'
                    ]
            ],
            [
                    'label' => Yii::t('backend', 'Order number'),
                    'value' => function($model) {
                        return $model->order['order_num'];
                    }
            ],
            [
                    'attribute' => 'expire_time',
                    'format' => 'date',
                    'filterInputOptions' => ['class' => 'form-control date']
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'visibleButtons' => [
                            'delete' => Yii::$app->user->can('ott-access/delete')
                    ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>

<?php Jsblock::begin() ?>
<script>
  laydate.render({
    elem: '.date'
  });
</script>
<?php Jsblock::end() ?>
