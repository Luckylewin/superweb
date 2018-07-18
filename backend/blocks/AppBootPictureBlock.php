<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/18
 * Time: 17:47
 */

namespace backend\blocks;


use backend\models\AppBootPicture;

class AppBootPictureBlock extends AppBootPicture
{
    const STATUS = ['无效', '有效'];

    static public function getStatus()
    {
        return self::STATUS;
    }

    public function getStatusText($status)
    {
        return self::STATUS[$status];
    }
}