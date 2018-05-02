<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/3/27
 * Time: 16:15
 */

namespace frontend\components;


use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class paypal
{
    public static function init()
    {
        //创建支付对象实例
        $clientId = 'AaSJexppeN-eFi72-QGe7MordT8_Fp2iVqfD0-U5vE-HYZG0EfaiNGrXJ3o1Jg6A25KoTzg4ssbOxu2L';
        $clientSecret = 'EA-5Fro9CfnPNUUiF27aS5SyC6u4Nf0tRfIPm4UNLOAUbaE_l4w5qiLIM446rNZE--3opb18jx0R99mo';
        return $apiContext = new ApiContext(new OAuthTokenCredential($clientId, $clientSecret));
    }
}