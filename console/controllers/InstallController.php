<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/21
 * Time: 16:46
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;

class InstallController extends Controller
{
    public function actionDb()
    {
        $path = Yii::getAlias('@console') . '/install';
        $profileSQL = "$path/sys_vod_profiles.sql";

        if (file_exists($profileSQL)) {
            $this->stdout("导入影片资料库" . PHP_EOL);
            $sqlArr = $_arr = explode(';', file_get_contents($profileSQL));

            foreach ($sqlArr as $key => $sql) {
                $this->stdout("导入第{$key}条数据".PHP_EOL);
                if ($sql) {
                    try {
                        Yii::$app->db->createCommand($sql)->execute();
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            $this->stdout("执行结束");
        }
    }
}