<?php
/**
 * 公共函数
 * @author 边走边乔 <771405950>
 */
namespace common\components;

use Yii;

class Func
{
    /**
     * 转换字节数为其他单位
     * @param string $fileSize 字节大小
     * @return string 返回大小
     */
    public static function sizeCount($fileSize)
    {
        if ($fileSize >= 1073741824) {
            $fileSize = round($fileSize / 1073741824 * 100) / 100 . ' GB';
        } elseif ($fileSize >= 1048576) {
            $fileSize = round($fileSize / 1048576 * 100) / 100 . ' MB';
        } elseif ($fileSize >= 1024) {
            $fileSize = round($fileSize / 1024 * 100) / 100 . ' KB';
        } else {
            $fileSize = $fileSize . ' Bytes';
        }
        return $fileSize;
    }

}