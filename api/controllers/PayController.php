<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/25
 * Time: 16:43
 */

namespace api\controllers;

use Yii;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

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
           ]
        ]);
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