<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('backend','Admin Management');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('backend','Admin Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <?=$this->render('_tab_menu');?>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['id' => 'grid'],
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            /*[
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
            ],*/
            'username',
            'email:email',
            [
                'attribute' => 'last_login_ip',
                'label' => '登录IP',
                'value' => function ($data) {
                    return long2ip($data->last_login_ip);
                }
            ],
            [
                    'attribute' => 'last_login_time',
                    'label' => '登录时间',
                    'value' => function($model) {
                        if (empty($model->last_login_time)) {
                            return \Yii::t('backend', 'Not Logged In');
                        }
                        return Yii::$app->formatter->asRelativeTime($model->last_login_time);
                    }
            ],
            [
                'attribute' => 'user_role',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::tag('span', $data->getGroup(), ['class' => 'label label-sm label-info']);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::tag('span', Yii::t('backend', $data->getStatus()), ['class' => 'label label-sm '.$data->getStatusStyle()]);
                }
            ],

            [
                'class' => 'common\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {auth} {delete}',
                'visibleButtons' => [
                    'delete' => Yii::$app->user->can('admin/delete')
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<?php //echo Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-success gridview']);?>
</div>
<?php
$this->registerJs('
    $(".gridview").on("click", function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
    });
');
?>