<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/30
 * Time: 16:00
 */
/* @var $todayNum */
/* @var $todaySum */
/* @var $totalSum */
/* @var $totalNum */

$this->registerJsFile('/statics/themes/default-admin/plugins/echarts/echarts.js')
?>

<div class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            今日订单数
        </div>
        <div class="panel-body">
            <h3>
                <?= $todayNum ?>
            </h3>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            今日订单金额
        </div>
        <div class="panel-body">
           <h3> <?= $todaySum; ?></h3>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            总订单数
        </div>
        <div class="panel-body">
           <h3>
               <?= $totalNum; ?>
           </h3>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            总订单金额
        </div>
        <div class="panel-body">
           <h3>
               <?= $totalSum; ?>
           </h3>
        </div>
    </div>
</div>

