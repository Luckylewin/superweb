<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/3/14
 * Time: 13:38
 */

namespace common\oss;

use Yii;
use OSS\OssClient;

class Aliyunoss
{
    public static $oss;
    public static $accessKeyId;
    public static $accessKeySecret;
    public static $endPoint;
    public static $bucket;

    public function __construct()
    {
        self::init();
    }

    public static function init()
    {
        self::$accessKeyId = Yii::$app->params['OSS']['ACCESS_ID'];
        self::$accessKeySecret = Yii::$app->params['OSS']['ACCESS_KEY'];
        self::$endPoint = Yii::$app->params['OSS']['ENDPOINT'];
        self::$bucket = Yii::$app->params['OSS']['BUCKET'];

        self::$oss = new OssClient(self::$accessKeyId, self::$accessKeySecret, self::$endPoint);
    }

    /**
     * 使用阿里云oss上传文件
     * @param $object string  保存到阿里云oss的文件名
     * @param $filePath string 文件在本地的绝对路径
     * @return bool     上传是否成功
     */
    public function upload($object, $filePath)
    {
        $res = false;
        $bucket = self::$bucket;               //获取阿里云oss的bucket
        if (self::$oss->uploadFile($bucket, $object, $filePath)) {  //调用uploadFile方法把服务器文件上传到阿里云oss
            $res = true;
        }

        return $res;
    }

    /**
     * 删除指定文件
     * @param $object string 被删除的文件名
     * @return bool   删除是否成功
     */
    public function delete($object)
    {
        $res = false;
        $bucket = self::$bucket;    //获取阿里云oss的bucket
        if (self::$oss->deleteObject($bucket, $object)){ //调用deleteObject方法把服务器文件上传到阿里云oss
            $res = true;
        }

        return $res;
    }

    protected function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $myDateTime = new \DateTime($dtStr);
        $expiration = $myDateTime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }

    public function getSignature($dir = 'user-dir/')
    {
        $id = self::$accessKeyId;
        $key = self::$accessKeySecret;
        $host = "http://". self::$bucket . "." . self::$endPoint;

        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start;

        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);

        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = [];
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;

        return $response;
    }

    public static function getDownloadUrl($object, $timeout = 60)
    {
        self::init();
        if (strpos($object, 'http://') !== false) {
            return $object;
        }
        return self::$oss->signUrl(self::$bucket, $object, $timeout);
    }

    public function test()
    {
        echo 'success';
    }
}