<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/9/12
 * Time: 16:43
 */

namespace console\controllers;

use backend\models\OttAccess;
use backend\models\OttGenreProbation;
use backend\models\OttOrder;
use common\models\OttChannel;
use common\models\OttLink;
use common\models\SubClass;
use yii\console\Controller;

class OttController extends Controller
{
    // 维护 OTT 分类状态
    public function actionUpdateAccess()
    {
        // 权限表
        foreach (OttAccess::find()->asArray()->each() as $access) {
            if ($access['is_valid'] && $access['expire_time'] < time()) {
                 // 判断过期的类型
                 // 1:查询试用期表
                 $probation = OttGenreProbation::findOne(['genre' => $access['genre'], 'mac' => $access['mac']]);
                 if (!empty($probation)) {
                    //继续查询是否有购买记录
                     $order = OttOrder::findOne(['genre' => $access['genre'], 'uid' => $access['mac']]);
                     if (empty($order)) {
                         $denyMsg = 'Expiry of probation';
                     } else {
                         $denyMsg = 'Expiry of Genre';
                     }

                     $data = ['is_valid' => '0', 'deny_msg' => $denyMsg];
                 } else {
                     $data = ['is_valid' => '0', 'deny_msg' => 'forbidden'];
                 }

                 OttAccess::updateAll($data, ['mac' => $access['mac'], 'genre' => $access['genre']]);
            }
        }
    }

    public function actionClear()
    {
        $subclass = SubClass::find()->all();
        foreach ($subclass as $class) {
            if (is_null($class->mainClass)) {
                $class->delete();
                $this->stdout("删除" . $class->name . PHP_EOL);
            }
        }

        $channels = OttChannel::find()->all();
        foreach ($channels as $channel) {
            if (is_null($channel->subClass)) {
                $channel->delete();
                $this->stdout('删除' . $channel->name . PHP_EOL);
            }
        }

        $links = OttLink::find()->all();
        foreach ($links as $link) {
            if (is_null($link->channel)) {
                $link->delete();
                $this->stdout('删除' . $link->link . PHP_EOL);
            }
        }
    }
}