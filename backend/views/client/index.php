<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;
use \yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sys Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sys Client', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'phone',
            [
                    'attribute' => 'admin_id',
                    'format' => 'raw',
                    'value' => function($model) {

                        if ($account = $model->account) {
                            return $account->username;
                        } else {
                            return Html::a("绑定帐号" , null , [
                                    'class' => 'btn btn-success btn-sm bind',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#blind-modal',
                                    'data-id' => $model->id,
                            ]);
                        }
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>
</div>

<?php

Modal::begin([
    'id' => 'blind-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">绑定后台帐号</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

$requestUrl = Url::to(['client/bind-account']);

$requestJs=<<<JS
     $(document).on('click', '.bind', function() {
                var id = $(this).attr('data-id');
                $.get('{$requestUrl}', {'id':id},
                    function (data) {
                        $('.modal-body').css('min-height', '200px').html(data);
                    }
                )
            })
JS;

$this->registerJs($requestJs);

Modal::end();

?>
