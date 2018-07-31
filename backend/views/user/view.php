<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\blocks\UserBlock*/

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php

    /* @var $model backend\blocks\UserBlock*/

    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
             //'auth_key',
             //'password_hash',
             //'password_reset_token',
            'email:email',
             //'status',
            //'allowance',
            //'allowance_updated_at',
            [
                    'attribute' => 'is_vip',
                    'value' => function($model) {
                        return $model->getVipText($model->is_vip);
                    }
            ],


            [
                'attribute' => 'identity_type',
                'value' => function($model) {
                    return $model->getTypeText($model->identity_type);
                }
            ],

            'vip_expire_time:datetime',
            'access_token',
            'access_token_expire:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['user/index'], ['class' => 'btn btn-default']) ?>
    </p>

</div>
