<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/23
 * Time: 17:22
 */

namespace console\models;

use common\models\Vod;
use console\traits\UpdateProfile;


class Variety extends Vod
{
    use UpdateProfile;

    public function collect($data, $playGroupName = 'default')
    {
        $this->work("Variety", $data, $playGroupName);

        return true;
    }
}