<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/16
 * Time: 14:24
 */

namespace common\grid;


use yii\grid\DataColumn;

class MyDataColumn extends DataColumn
{
    public function init()
    {
        parent::init();
        if (!empty($this->attribute)) {
            $this->headerOptions = array_merge($this->headerOptions, [
                'class' => $this->attribute,
            ]);
            $this->contentOptions = array_merge($this->contentOptions, [
                'class' => $this->attribute,
            ]);
        }
    }
}