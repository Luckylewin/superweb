<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/25
 * Time: 16:43
 */

namespace api\controllers;

use api\components\Formatter;
use common\models\BuyRecord;
use Yii;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\User;

class PayController extends ActiveController
{
    public $modelClass = '';


    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
           'verbs' => [
               'class' => VerbFilter::className(),
               'actions' => [
                   'notify' => ['POST']
               ]
           ],
            'authenticator' => [
                'class' => QueryParamAuth::className()
            ],

        ]);
    }

    public function actionVip()
    {
        $user_id = Yii::$app->user->getId();
        if ($user = \common\models\User::findOne($user_id)) {
            $user->is_vip = 1;
            $user->vip_expire_time = 1591234123;
            $user->save(false);
            return $user;
        }
        return Formatter::format([],0, ['已经是会员']);
    }

    public function actionPay()
    {
        $vod_id = Yii::$app->request->post('vod_id');
        $user_id = Yii::$app->user->getId();
        if ($vod_id && $user_id) {
            $record = BuyRecord::findOne(['vod_id' => $vod_id, 'user_id' => $user_id]);
            if (is_null($record)) {
                $record = new BuyRecord();
                $record->user_id = $user_id;
                $record->vod_id = $vod_id;
                $record->save(false);
                return Formatter::format([], Formatter::SUCCESS, '支付成功');
            }
            return Formatter::format([], Formatter::SUCCESS, '已经拥有');
        }
    }

    public function actionNotify()
    {
        $config = Yii::$app->params['ALI_PAY'];
        $alipay = Pay::alipay($config);
        $data = $alipay->verify();
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            var_dump($data);
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况

            Log::debug('Alipay notify', $data->all());
        } catch (\Exception $e) {

            echo $e->getMessage();
        }

        return $alipay->success()->send();// laravel 框架中请直接 `return $alipay->success()`
    }

}