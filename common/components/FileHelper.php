<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/12
 * Time: 16:12
 */

namespace common\components;

use \yii\helpers\FileHelper as SystemFileHelper;

class FileHelper extends SystemFileHelper
{
    public static function scandir($directory, $withPath = false)
    {
        $directory = rtrim($directory, '/');
        $fileLists = scandir($directory);
        $list = [];

        foreach ($fileLists as $file) {
            if (!in_array($file, ['.', '..'])) {
                if ($withPath) {
                    $list[] = $directory . '/' . $file;
                } else {
                    $list[] = $file;
                }
            }
        }

        return $list;
    }
}