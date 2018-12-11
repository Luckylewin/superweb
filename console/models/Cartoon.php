<?php

namespace console\models;

use common\models\Vod;
use console\traits\UpdateProfile;


class Cartoon extends Vod
{
    use UpdateProfile;

    public function collect($data, $playGroupName = 'default')
    {
        $this->work("Cartoon", $data, $playGroupName);

        return true;
    }
}