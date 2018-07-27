<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/27
 * Time: 17:02
 */

namespace backend\blocks;


use backend\models\RenewalCard;

class RenewCardBlock extends RenewalCard
{
    public static $typeSelect = [
        '7 day' => '7天',
        '15 day' => '15天',
        '1 month' => '一个月',
        '3 month' => '三个月',
        '6 month' => '六个月',
        '12 month' => '一年',
    ];
}