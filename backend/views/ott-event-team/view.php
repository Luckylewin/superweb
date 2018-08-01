<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OttEventTeam */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ott Event Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-event-team-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Go Back'), Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'event_id',
            'team_name',
            'team_zh_name',
            'team_introduce',
            'team_icon',
            'team_icon_big',
            'team_country',
            'team_info',
            'team_alias_name',
        ],
    ]) ?>

</div>
