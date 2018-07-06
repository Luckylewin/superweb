<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/12
 * Time: 11:13
 */

namespace console\models;


use Snoopy\Snoopy;

class common
{
    /**
     * 判断播放错误
     * @param $name
     * @param $link
     * @param $result
     * @return array
     */
    public static function getStatus($name,$link,$result)
    {
        if (strlen($result)>0) {
            if(strpos($result,'110') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 连接超时\n{$link}\n",'error'=>'超时');
            }
            elseif(strpos($result,'111') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 连接被拒绝\n{$link}\n",'error'=>'拒绝');
            }
            elseif(strpos($result,'404') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 服务器返回404\n{$link}\n",'error'=>'404');

            }elseif(strpos($result,'Server returned 403 Forbidden') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 认证错误\n{$link}\n",'error'=>'Forbidden');
            }
            elseif(strpos($result,'Server returned 5XX') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 服务器内部错误\n{$link}\n",'error'=>'5XX');
            }
            elseif(strpos($result,'End of file') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] End of file\n{$link}\n",'error'=>'End of file');

            }elseif(strpos($result,'-5') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 输入/输出错误\n{$link}\n",'error'=>'输入输出错误');

            }elseif(strpos($result,'-104') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 连接被重置\n{$link}\n",'error'=>'被重置');

            }elseif(strpos($result,'-113') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] No route to host\n{$link}\n",'error'=>'No route to host');

            }elseif(strpos($result,'Operation not permitted') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 操作被拒绝\n{$link}\n",'error'=>'Operation not permitted');
                //
            }elseif (strpos($result,'Invalid data') !== false) {
                return array('status'=>false,'msg'=>$name . "节目源 连接[失败] 无效的数据\n{$link}\n",'error'=>'数据无效');
            }else {
                return array('status'=>true,'msg'=>$name . "节目源 连接[成功]!\n{$link}\n",'error'=>'');
            }
        } else {
            return array('status'=>false,'msg'=> $name."节目源 连接[失败]\n{$link}\n",'error'=>'未知');
        }
    }

    /**
     * 根据urlID获取真实播放地址
     * @param $url
     * @return array|bool
     */
    public static function getVideoUrl($url)
    {
        $Snnopy = new Snoopy();
        $Snnopy->scheme = 'https';
        $Snnopy->_fp_timeout = 15;

        try {
            $Snnopy->fetch('https://www.youtube.com/get_video_info?video_id='.$url);
            $videoInfo = $Snnopy->results;
        }catch (\Exception $e) {
             return false;
        }

        if (empty($videoInfo)) {
            return false;
        }
        parse_str($videoInfo ,$info);
        if (!isset($info['url_encoded_fmt_stream_map'])) {
            return false;
        }
        $streams = explode(',',$info['url_encoded_fmt_stream_map']);
        $video = [];

        foreach($streams as $stream) {
            parse_str($stream, $real_stream);
            //$video[$real_stream['quality']] = $real_stream['url'];
            if (isset($real_stream['url'])) {
                return $real_stream['url'];
            }
        }
        if (empty($video)) {
            return false;
        }

        return $video;
    }

    /**
     * 获取中文/英文首字母
     * @param  string $str
     * @return string
     */
    public static function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }

        if (preg_match('/^[a-zA-Z]+/', $str)) {
            return strtoupper(substr($str, 0 , 1));
        }

        if (preg_match('/^[0-9]/', $str)) {
            $str = substr($str, 0 ,1);
            $map = ['L','Y','E','S','S','W','L','Q','B','J','S'];
            return $map[$str];
        }

        if (is_numeric($str)) {
            return (int)$str;
        }

        $firstChar = ord($str{0});

        if ($firstChar >= ord('A') && $firstChar <= ord('z')) {
            return strtoupper($str{0});
        }

        $s1 = @iconv('UTF-8','gb2312',$str);
        $s2 = @iconv('gb2312','UTF-8',$s1);

        $s = $s2 == $str ? $s1 : $str;

        @$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;

        if($asc>=-20319&&$asc<=-20284) return 'A';
        if($asc>=-20283&&$asc<=-19776) return 'B';
        if($asc>=-19775&&$asc<=-19219) return 'C';
        if($asc>=-19218&&$asc<=-18711) return 'D';
        if($asc>=-18710&&$asc<=-18527) return 'E';
        if($asc>=-18526&&$asc<=-18240) return 'F';
        if($asc>=-18239&&$asc<=-17923) return 'G';
        if($asc>=-17922&&$asc<=-17418) return 'H';
        if($asc>=-17417&&$asc<=-16475) return 'J';
        if($asc>=-16474&&$asc<=-16213) return 'K';
        if($asc>=-16212&&$asc<=-15641) return 'L';
        if($asc>=-15640&&$asc<=-15166) return 'M';
        if($asc>=-15165&&$asc<=-14923) return 'N';
        if($asc>=-14922&&$asc<=-14915) return 'O';
        if($asc>=-14914&&$asc<=-14631) return 'P';
        if($asc>=-14630&&$asc<=-14150) return 'Q';
        if($asc>=-14149&&$asc<=-14091) return 'R';
        if($asc>=-14090&&$asc<=-13319) return 'S';
        if($asc>=-13318&&$asc<=-12839) return 'T';
        if($asc>=-12838&&$asc<=-12557) return 'W';
        if($asc>=-12556&&$asc<=-11848) return 'X';
        if($asc>=-11847&&$asc<=-11056) return 'Y';
        if($asc>=-11055&&$asc<=-10247) return 'Z';

        return false;
    }


}