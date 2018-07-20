<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = '分配角色';
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <?=$this->render('_tab_menu');?>

    <?php ActiveForm::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'caption' => "<h3>{$user->username}</h3>",
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label' => '角色名称',
                'format' => 'raw',
                'value' => function ($data) use ($adminGroups) {
                    return Html::radio('roleName', in_array($data->name, $adminGroups) ? true : false, ['value' => $data->name]) .' '. $data->name;
                },
            ],
            [
                'attribute' => 'description',
                'label' => '描述'
            ],
        ],
    ]); ?>
    <div class="form-group">

        <?=Html::submitButton('保存', ['class' => 'btn btn-info']) ?>
        <?= Html::a('返回', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- index -->
