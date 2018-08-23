<?php
/**
 * 公共函数
 * @author 边走边乔 <771405950>
 */
namespace common\components;

use common\oss\Aliyunoss;
use Yii;

class Func
{
    /**
     * 转换字节数为其他单位
     * @param string $fileSize 字节大小
     * @return string 返回大小
     */
    public static function sizeCount($fileSize)
    {
        if ($fileSize >= 1073741824) {
            $fileSize = round($fileSize / 1073741824 * 100) / 100 . ' GB';
        } elseif ($fileSize >= 1048576) {
            $fileSize = round($fileSize / 1048576 * 100) / 100 . ' MB';
        } elseif ($fileSize >= 1024) {
            $fileSize = round($fileSize / 1024 * 100) / 100 . ' KB';
        } else {
            $fileSize = $fileSize . ' Bytes';
        }
        return $fileSize;
    }

    /**
     * 转换数值大小
     * @param $size
     * @return string
     */
    public function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    /**
     * 获取服务器IP
     * @return mixed|null|string
     */
    public static function getServerIp()
    {
        if (isset(Yii::$app->request->hostName)) {
            return Yii::$app->request->hostName;
        } else if (isset(Yii::$app->params['serverDomain'])) {
            return Yii::$app->params['serverDomain'];
        } else if (isset(Yii::$app->params['serverIP'])) {
            return Yii::$app->params['serverIP'];
        } else {
            return getHostByName(getHostName());
        }
    }

    /**
     * nginx防盗链
     * @param $path
     * @param int $expireTime
     * @return string
     */
    public static function getAccessUrl($path, $expireTime = 300)
    {
        if (empty($path)) {
            return 'null';
        }

        $httpPos = strpos($path,'http');

        if ($httpPos === 0) {
            return $path;
        }

        if ($httpPos === false && strpos($path, '/') !== 0) {
            return Aliyunoss::getDownloadUrl($path, 300);
        }

        $url = "http://" . self::getServerIp() . ":" . Yii::$app->params['nginx']['media_port'] ."{$path}?";

        $mac = '287994000001';//mac地址
        $secret = Yii::$app->params['nginx']['secret']; //加密密钥
        $expire = time() + $expireTime;//链接有效时间
        $md5 = md5($secret.$expire, true); //生成密钥与过期时间的十六位二进制MD5数，
        $md5 = base64_encode($md5);// 对md5进行base64_encode处理
        $md5 = str_replace(array('=','/','+'), array('','_','-'), $md5); //分别替换字符，去掉'='字符, '/'替换成'_','+'替换成'-'
        $key = md5($secret.$mac.$path); //对密钥, mac地址,芯片序列号sn,资源路径path进行MD5处理
        $url .= "st={$md5}&e={$expire}&key={$key}&mac={$mac}";//最后拼接

        return $url;
    }

    /**
     * 设置文件下载请求头
     */
    public static function setDownloadFileHeader($filename)
    {
        $response = Yii::$app->response;
        $response->format = $response::FORMAT_RAW;

        $response->headers->set('Content-Type', 'application/text');
        $response->headers->set('Content-Disposition', "attachment;filename=" . $filename);
        $response->headers->set('Cache-Control', 'max-age=0');
    }

    public static function setDownloadZipHeader($filepath, $filename)
    {
        $response = Yii::$app->response;
        $response->format = $response::FORMAT_RAW;

        header('Content-Type: application/zip;charset=utf8');
        header('Content-disposition: attachment; filename=' . $filename);
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
    }
}