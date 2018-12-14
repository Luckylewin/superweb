<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

?>
<div class="role-index">

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

        <?=Html::submitButton(Yii::t('backend', 'Auth'), ['class' => 'btn btn-info col-md-12']) ?>

    </div>
    <?php ActiveForm::end(); ?>

</div><!-- index -->
