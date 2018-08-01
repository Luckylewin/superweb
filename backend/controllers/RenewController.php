<?php

namespace backend\controllers;

use Yii;
use backend\components\MyRedis;
use backend\models\Mac;
use backend\models\RenewLog;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * RenewController implements the CRUD actions for RechargeCard model.
 */
class RenewController extends BaseController
{
    public function actionRenew($mac)
    {

        if (Yii::$app->request->isPost) {
            $mac = Yii::$app->request->get('mac');
            $mac = Mac::findOne(['MAC' => $mac]);

            $error = false;
            if ($mac->use_flag === Mac::NOT_ACTIVE) {
                Yii::$app->session->setFlash('error', "该帐号未激活");
                $error = true;
            } else if (strtotime($mac->duetime) == strtotime('1970')) {
                Yii::$app->session->setFlash('error', "该帐号为无限期");
                $error = true;
            } else if (is_null($mac)) {
                Yii::$app->session->setFlash('error', "数据不存在");
                $error = true;
            } else if (Yii::$app->request->post('contract_time') <= 0) {
                Yii::$app->session->setFlash('error', "续费时间错误");
                $error = true;
            }

            if ($error) {
                return $this->redirect(Url::to(['renew/renew', 'mac' => $mac->MAC]));
            }

            $renewTime = Yii::$app->request->post('contract_time') . " ". Yii::$app->request->post('unit');



            //判断是否已经过期了
            if ($mac->use_flag == Mac::NORMAL) {
                $mac->duetime = date('Y-m-d H:i:s', strtotime("+ $renewTime",strtotime($mac->duetime)));

            } else if ($mac->use_flag == Mac::EXPIRED) {
                $mac->duetime = date('Y-m-d H:i:s', strtotime("+ $renewTime"));
                $mac->use_flag = Mac::NOT_ACTIVE;
            }

            $mac->contract_time = $renewTime;
            //更新数据库,缓存
            $mac->save(false);
            MyRedis::init(MyRedis::REDIS_DEVICE_STATUS);
            Yii::$app->session->setFlash('success', "续费操作成功");

            //记录日志
            $renewLog = new RenewLog();
            $renewLog->mac = $mac->MAC;
            $renewLog->renew_period = $renewTime;
            $renewLog->expire_time = $mac->duetime;
            $renewLog->card_num = '-';
            $renewLog->renew_operator = '0';
            $renewLog->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        }

        $mac = Mac::findOne(['MAC' => $mac]);

        $renewRecord = ArrayHelper::toArray(RenewLog::find()->where(['mac' => $mac->MAC])->all());

        return $this->render('renew',[
            'model' => $mac,
            'renewRecord' => $renewRecord
        ]);
    }
}
