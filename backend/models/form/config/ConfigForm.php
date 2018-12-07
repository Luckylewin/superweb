<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 12:56
 */

namespace backend\models\form\config;


use yii\base\Model;

abstract class ConfigForm extends Model
{
    use LoadData;

    /**
     * @return $this
     */
    abstract function setData();

}