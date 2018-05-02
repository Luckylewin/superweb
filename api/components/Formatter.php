<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/28
 * Time: 13:45
 */

namespace api\components;


class Formatter
{

    const SUCCESS = 0;
    /**
     * 系统规则错误 无权限等等
     */
    const INVALID_ERROR = 403;
    const SERVER_ERROR = 500;


    const ITEM_NOT_FOUND = 800;

    /**
     * 业务错误 登录 注册等待
     */
    const MAC_NOT_EXIST = 990;
    const USERNAME_NOT_EXIST = 991;
    const MAC_EXPIRE = 992;
    const MAC_FORBIDDEN = 993;

    const EMPTY_LOGIN_ERROR = 1001;
    const LOGIN_PASSWORD_ERROR = 1002;

    const EMPTY_SIGNUP_ERROR = 1003;
    const USERNAME_EXIST_ERROR = 1004;
    const PASSWORD_TOO_SHORT_ERROR = 1005;

    //app升级
    const NO_NEED_UPDATE = 1010;

    //影片业务
    const FREE_OF_CHARGE = 1101;



    public static function getMessage($errorCode)
    {
        switch ($errorCode)
        {
            case self::SERVER_ERROR:
                return '服务器暂不可用';break;
            case self::INVALID_ERROR:
                return '非法的请求';break;
            case self::EMPTY_LOGIN_ERROR:
                return '登录字段不能为空';break;
            case self::EMPTY_SIGNUP_ERROR:
                return '注册字段不能为空';break;
            case self::USERNAME_EXIST_ERROR:
                return '用户名已经存在';break;
            case self::PASSWORD_TOO_SHORT_ERROR:
                return '密码长度不足6位';break;
            case self::LOGIN_PASSWORD_ERROR:
                return '用户名/密码不匹配';break;
            case self::MAC_NOT_EXIST:
                return '账户不存在';break;
            case self::MAC_EXPIRE:
                return '账户过期';break;
            case self::MAC_FORBIDDEN:
                return '账户被禁用';break;
            default:
                return '其他错误';break;

        }
    }

    /**
     *
     *
     * {
     *   data : {
            // 请求数据，对象或数组均可
     *      user_id: 123,
     *      user_name: "test",
     *      user_avatar_url: "http://tutuge.me/avatar.jpg"
     *      ...
     *      },
     *   msg : "done" // 请求状态描述，调试用
     *   code: 1001   // 业务自定义状态码
     * }

     * @param $data
     * @param $msg
     * @param $code
     * @return array
     */
    public static function format($data, $code = 0, $msg = 'success' )
    {
        return [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];
    }
}