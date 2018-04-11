<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/9
 * Time: 9:55
 */

namespace backend\components;


class MySSH
{
    private static $singleton;
    private static $ssh_connection;

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __construct()
    {
        self::conn();
    }

    public static function conn($user='',$pass='')
    {
        $ssh = \Yii::$app->params['ssh'];

        $user = empty($user)?$ssh['username'] : $user;
        $pass = empty($pass)?$ssh['password'] : $pass;
        try{
            $connection = ssh2_connect($ssh['host'], $ssh['port']);
            $authResult = ssh2_auth_password($connection,$user,$pass);
            self::$ssh_connection = $connection;
        }catch (\Exception $e){
            return false;
        }
        return $authResult;
    }

    /**
     * 单例模式
     * @return MySSH
     */
    public static function singleton()
    {
        if (! self::$singleton instanceof MySSH) {
            self::$singleton = new self();
        }
        return self::$singleton;
    }

    public function exec($cmd,$async = false)
    {
        if ( strpos($cmd, 'dev/null') === false && $async ) {
            $cmd .= ' > /dev/null 2>&1 &';
        }
        $stream = ssh2_exec(self::$ssh_connection,$cmd);
        stream_set_blocking($stream, true);
        return stream_get_contents($stream);
    }

    public function close()
    {
        $this->exec("close");
    }

}