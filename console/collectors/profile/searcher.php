<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/12
 * Time: 15:33
 */

namespace console\collectors\profile;

/**
 * 搜索器抽象类
 * Class searcher
 * @package console\collectors\profile
 */
abstract class searcher
{
    // 搜索器支持的语言
    public $supportedLanguages;

    // 设置搜索器支持的语言
    abstract public function setSupportedLanguage();

    public function getSupportedLanguage()
    {
        $this->setSupportedLanguage();

        return $this->supportedLanguages;
    }
}