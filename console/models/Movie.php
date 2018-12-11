<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/15
 * Time: 13:43
 */

namespace console\models;

use common\models\Vod;
use console\traits\UpdateProfile;


class Movie extends Vod
{
    use UpdateProfile;

    public function collect($data, $playGroupName = 'default')
    {
        $this->work("Movie", $data, $playGroupName);

        return true;
    }


}