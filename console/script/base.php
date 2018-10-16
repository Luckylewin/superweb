<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/7
 * Time: 13:53
 */

namespace console\script;


use yii\console\Controller;
use yii\helpers\Console;

class base
{
    /**
     * @var Controller
     */
    public $controller;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    protected function stdout($str, $color = Console::FG_GREEN, $background = null)
    {
       if ($this->controller instanceof Controller) {
           if ($background) {
               return $this->controller->stdout($str, $color, $background);
           } else {
               return $this->controller->stdout($str, $color);
           }
       }

       echo $str;
    }

}