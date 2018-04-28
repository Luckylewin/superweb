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



    /**
     * 业务错误 登录 注册等待
     */
    const EMPTY_LOGIN_ERROR = 1001;
    const EMPTY_SIGNUP_ERROR = 1002;
    const NO_NEED_UPDATE = 1010;
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