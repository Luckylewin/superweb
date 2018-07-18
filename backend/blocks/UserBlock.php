<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/18
 * Time: 16:47
 */

namespace backend\blocks;


use common\models\User;

class UserBlock extends User
{
    const VIP_STATUS = ['游客', '会员'];
    const VIP_TYPE = ['试用会员', '付费会员'];

    // 身份
    public function getVipText($type)
    {
        return self::VIP_STATUS[$type];
    }

    // 会员类型
    public function getTypeText($type)
    {
        return self::VIP_TYPE[$type];
    }

}