<?php

use \yii\bootstrap\Html;

$this->title = Yii::t('backend', 'Renewal');
$this->params['breadcrumbs'][] = Yii::t('backend', 'User List');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="center">
    <?= Html::beginForm(\yii\helpers\Url::to(['renew/renew','mac'=>$model->MAC ]), 'POST'); ?>

     <div>
         <label for="mac"><?= Yii::t('backend', 'Current operation object') ?>(<?= $model->getUseFlag($model->use_flag) ?>)</label>
         <?= Html::input('text', "mac", $model->MAC,[
             'class' => 'form-control',
             'readonly' => 'true',
             'style' => 'width:400px;'
         ]); ?>
     </div> <br/>

    <div>
        <label for="unit"><?= Yii::t('backend', 'time unit') ?></label>
        <?= Html::dropDownList('unit','year', \backend\models\Mac::getContractList(),
            [
                    'class' => 'form-control',
                    'style' => 'width:400px;'
            ]
        ); ?>
    </div> <br/>

    <div>
        <label for="text"><?= Yii::t('backend', 'Time') ?></label>
        <?= Html::input('text', 'contract_time', 1 , [
                'class' => 'form-control',
                'style' => 'width:400px;'
        ]) ?>

    </div> <br/>

    <div>
        <?= Html::submitButton(Yii::t('backend', 'Renewal'), [
                'class' => 'btn btn-success'
        ]) ?>

        <?= Html::a(Yii::t('backend', 'Go Back'),\yii\helpers\Url::to(['mac/index']) ,[
            'class' => 'btn btn-default'
        ]) ?>

    </div>

    <?= Html::endForm(); ?>
</div>


<br><hr><br/>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= Yii::t('backend', 'History Record') ?>
        </h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th><?= Yii::t('backend', 'Renewal date') ?></th>
                    <th><?= Yii::t('backend', 'Renewal card number') ?></th>
                    <th><?= Yii::t('backend', 'Renewal time') ?></th>
                    <th><?= Yii::t('backend', 'Expire Time') ?></th>
                    <th><?= Yii::t('backend', 'Renewal method') ?></th>
                </tr>

                </thead>
                <tbody>
                <?php foreach($renewRecord as $record): ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= date('Y-m-d',$record['date']) ?></td>
                        <td><?= $record['card_num'] ?></td>
                        <td><?= Yii::t('backend', $record['renew_period']); ?></td>
                        <td>
                            <?= $record['expire_time'];?>
                        </td>
                        <td>
                            <?php if(!$record['renew_operator']): ?>
                                <span class="label label-success">
                                    <?= Yii::t('backend', 'Background operation') ?>
                            </span>
                            <?php else: ?>
                                <span class="label label-primary">
                                    <?= Yii::t('backend', 'Renewal card') ?>
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

