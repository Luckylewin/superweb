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
            return $real_stream['url'];
        }
        if (empty($video)) {
            return false;
        }

        return $video;
    }
}