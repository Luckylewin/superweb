<?php

use \yii\bootstrap\Html;

$this->title = '续费';
$this->params['breadcrumbs'][] = "用户列表";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="center">
    <?= Html::beginForm(\yii\helpers\Url::to(['renew/renew','mac'=>$model->MAC ]), 'POST'); ?>

     <div>
         <label for="mac">当前操作对象(<?= $model->getUseFlag($model->use_flag) ?>)</label>
         <?= Html::input('text', "mac", $model->MAC,[
             'class' => 'form-control',
             'readonly' => 'true',
             'style' => 'width:400px;'
         ]); ?>
     </div> <br/>

    <div>
        <label for="unit">时间单位</label>
        <?= Html::dropDownList('unit','year', ['year' => '年','month' => '月', 'day' => '天'],
            [
                    'class' => 'form-control',
                    'style' => 'width:400px;'
            ]
        ); ?>
    </div> <br/>

    <div>
        <label for="text">时间</label>
        <?= Html::input('text', 'contract_time', 1 , [
                'class' => 'form-control',
                'style' => 'width:400px;'
        ]) ?>

    </div> <br/>

    <div>
        <?= Html::submitButton("续费", [
                'class' => 'btn btn-success'
        ]) ?>

        <?= Html::a("返回",\yii\helpers\Url::to(['mac/index']) ,[
            'class' => 'btn btn-default'
        ]) ?>

    </div>

    <?= Html::endForm(); ?>
</div>


<br><hr><br/>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            续费历史记录
        </h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>续费日期</th>
                    <th>续费卡号</th>
                    <th>续费时长</th>
                    <th>过期时间</th>
                    <th>续费方式</th>
                </tr>

                </thead>
                <tbody>
                <?php foreach($renewRecord as $record): ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= date('Y-m-d',$record['date']) ?></td>
                        <td><?= $record['card_num'] ?></td>
                        <td><?= str_replace([' day',' month',' year'],['天','个月','年'],$record['renew_period']); ?></td>
                        <td>
                            <?= $record['expire_time'];?>
                        </td>
                        <td>
                            <?php if(!$record['renew_operator']): ?>
                                <span class="label label-success">
                                后台操作
                            </span>
                            <?php else: ?>
                                <span class="label label-primary">
                                续费卡
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

