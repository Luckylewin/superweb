<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/3/27
 * Time: 16:17
 */

namespace frontend\controllers;


use common\models\ActivateLog;
use common\models\BuyRecord;
use common\models\Order;
use common\models\UpgradeRecord;
use common\models\User;
use frontend\components\paypal;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class PayController extends Controller
{
    public function actionCreate($order)
    {
        $order = Order::findOne(['order_sign' => $order]);
        if (!$order) {
            throw new NotFoundHttpException("Sorry, an error occur", 404);
        }
        $product = $order->order_info;
        $price = $order->order_money;
        $shipping = 0.00; //运费
        $invoice_number = $order->order_sign; //订单号
        $successCallback = Yii::$app->urlManager->createAbsoluteUrl(['pay/callback','success'=>'true']);   //成功支付回调
        $cancelCallback = Yii::$app->urlManager->createAbsoluteUrl(['pay/callback','success'=>'false']);  //取消回调
        $total = $price; //总金额
        $quantity = 1; //数量
        $currency = 'USD'; //货币
        $description = $order->order_info;  //订单描述信息

        //创建paypal对象
        $apiContext = paypal::init();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
            ->setCurrency($currency)
            ->setQuantity($quantity)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $details = new Details();
        $details->setShipping($shipping)
            ->setSubtotal($price);

        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($total)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($description)
            ->setInvoiceNumber($invoice_number);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($successCallback)   //设置支付成功回调地址
        ->setCancelUrl($cancelCallback); //设置支付失败回调地址

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($apiContext);
        } catch (PayPalConnectionException $e) {
            throw new NotFoundHttpException($e->getData,"404");
        }

        $approvalUrl = $payment->getApprovalLink();
        return \Yii::$app->response->redirect($approvalUrl);
        //header("Location:{$approvalUrl}");
    }

    public function actionCallback()
    {
        if (Yii::$app->request->get('success') == 'false') {
            die('<script>alert("transaction cancel")</script>');
        }

        if (!Yii::$app->request->get('success') ||
            !Yii::$app->request->get('paymentId') ||
            !Yii::$app->request->get('PayerID')
        ) {
            die('<script>alert("error params")</script>');
        }

        $paymentID = Yii::$app->request->get('paymentId');
        $payerId = Yii::$app->request->get('PayerID');

        $apiContext = paypal::init();
        $payment = Payment::get($paymentID, $apiContext);

        $execute = new PaymentExecution();
        $execute->setPayerId($payerId);

        try{
            $result = $payment->execute($execute, $apiContext);
            Yii::$app->session->setFlash('success', "pay success");

            $result = $this->object_array($result);
            $result = current(array_values($result));
            $result = current($result['transactions']);
            $result = array_values($result)[0];
            $invoice_number = $result['invoice_number'];

            if (!empty($invoice_number)) {
                $order = Order::findOne(['order_sign' => $invoice_number]);
                $order->order_confirmtime = time();
                $order->order_paytime = time();
                $order->order_status = 1;
                $order->order_ispay = 1;

                $order->save(false);

                //更新用户状态
                switch ($order->order_type)
                {
                    case 'vod':
                        $record = BuyRecord::findOne(['order_id' => $order->order_id]);
                        $record->is_valid = 1;
                        $record->save(false);
                        break;
                    case 'vip':
                        $record = UpgradeRecord::findOne(['order_id' => $order->order_id]);
                        $record->is_deal = 1;
                        if ($record) {
                            $user = User::findOne($record->user_id);
                            $user->vip_expire_time = $record->expire_time;
                            $user->is_vip = 1;
                            $user->save(false);
                        }
                        $record->save(false);

                }


                return Yii::$app->response->redirect(Url::to(['site/success', 'order' =>  $invoice_number]));
            }
        }catch(\Exception $e){
            throw new ForbiddenHttpException("该订单已经支付过了，请勿刷新");
        }
    }


    private  function object_array($array)
    {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = $this->object_array($value);
        }
    }
        return $array;
    }
}