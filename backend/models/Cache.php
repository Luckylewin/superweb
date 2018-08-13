<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/20
 * Time: 10:07
 */

namespace backend\models;

use common\components\Func;
use Yii;
use common\models\MainClass;
use common\models\SubClass;
use common\models\OttChannel;
use backend\components\MyRedis;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use DOMDocument;

class Cache
{
    public static $JSON = 'json';
    public static $XML = 'xml';

    public function createOttCache($id, $format = 'json')
    {
        //方案号
        $schemes = Scheme::find()->all();

        if ($format == 'json') {
            //缓存全部方案
            $this->setJsonCache($id, null);
            //按方案号缓存
            foreach ($schemes as $scheme) {
                $this->setJsonCache($id, $scheme);
            }
        } else {
            $this->setXMLCache($id, null);
            //按方案号缓存
            foreach ($schemes as $scheme) {
                $this->setXMLCache($id, $scheme);
            }
        }
    }

    /**
     * @param $id
     * @param Scheme|null $scheme
     * @return mixed
     */
    private function setJsonCache($id, $scheme)
    {
        $mainClass = MainClass::findOne($id);

        $data['version'] = time();
        $data['scheme'] = is_null($scheme) ? 'ALL' : $scheme->schemeName;
        $data['name'] = $mainClass->name;
        $data['zh_name'] = $mainClass->zh_name;
        $data['icon'] = $mainClass->icon;
        $data['description'] = $mainClass->description;
        $data['subClass'] = $this->getSubClassLink($mainClass, $scheme);
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $redis->set("OTT_LIST_JSON_{$data['name']}_{$data['scheme']}", Json::encode($data));
        $redis->set("OTT_LIST_JSON_{$data['name']}_{$data['scheme']}_VERSION", $data['version']);

        return $data;
    }

    /**
     * 获得缓存版本
     * @param $name
     * @return mixed
     */
    public function getCacheVersion($name)
    {
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        return $redis->get("OTT_LIST_JSON_{$name}_ALL_VERSION");
    }

    /**
     * @param MainClass $mainClass
     * @param $mainClass
     * @param $scheme
     * @return array
     */
    public function getSubClassLink($mainClass, $scheme)
    {
        //查询子分类
        $items = [];
        $subClass = $mainClass->getSub(['use_flag' => 1])->all();
        //查询频道
        if (!empty($subClass)) {
            foreach ($subClass as $class) {
                if ($result = $this->getChannel($class, $scheme)) {
                    $_subClass = ArrayHelper::toArray($class);
                    $_subClass['channels'] = $result;
                    $items[] = $_subClass;
                }
            }
        }

        return $items;
    }

    /**
     * @param SubClass $class
     * @param $scheme
     * @return bool|array
     */
    private function getChannel($class, $scheme)
    {
        $items = [];
        $channels = $class->getOwnChannel(['use_flag' => 1])->orderBy('name asc')->all();

        if (empty($channels)) {
            return false;
        }

        foreach ($channels as $channel) {
            if ($channel instanceof OttChannel) {
                if ($links = $this->getLink($channel, $scheme)) {
                    $channel = ArrayHelper::toArray($channel);
                    $channel['links'] = $links;
                    $items[] = $channel;
                }
            }
        }

        return empty($items) ? false : $items;
    }

    /**
     * @param OttChannel $channel
     * @param $channel
     * @param $scheme
     * @return array|bool
     */
    private function getLink($channel, $scheme)
    {
        $items = [];
        //查询链接
        $links = $channel->getOwnLink(['use_flag' => 1])->all();
        if (!empty($links)) {
            foreach ($links as $link) {
                $flag = false;
                if ($link['scheme_id'] == 'all' || is_null($scheme)) {
                    $flag = true;
                } else {
                    $scheme_id = explode(',', $link['scheme_id']);
                    if (in_array($scheme->id, $scheme_id)) {
                        $flag = true;
                    }
                }

                if ($flag) {
                    unset($link['use_flag_text']);
                    unset($link['scheme_id']);
                    unset($link['schemeText']);
                    $items[] = $link;
                }

            }
        }

        return empty($items) ? false : $items;
    }


    /**
     * @param $id
     * @param Scheme|null $scheme
     * @return bool|DOMDocument
     */
    private function setXMLCache($id, $scheme)
    {
        $dom = new DomDocument('1.0', 'utf-8');
        $response = $dom->createElement('response');
        $dom->appendchild($response);
        $response->setAttribute("method", "getLiveList");

        $attributes=$dom->createElement("attributes");
        $response->appendChild($attributes);

        $schemeNode = $dom->createElement("scheme");
        $attributes->appendChild($schemeNode);
        $schemeName = is_null($scheme) ? 'ALL' : $scheme->schemeName;
        $value = $dom->createTextNode($schemeName);
        $schemeNode->appendChild($value);

        $needUpdate = $dom->createElement("needUpdate");
        $attributes->appendChild($needUpdate);
        $value = $dom->createTextNode("true");
        $needUpdate->appendChild($value);

        $ipaddr=$dom->createElement("ipaddr");
        $attributes->appendChild($ipaddr);
        $value = $dom->createTextNode(Yii::$app->request->hostName);
        $ipaddr->appendChild($value);

        $newVersion=$dom->createElement("newVersion");
        $attributes->appendChild($newVersion);
        $version = date('YmdHis',time());
        $value = $dom->createTextNode($version);
        $newVersion->appendChild($value);

        $mainClass = MainClass::findOne($id);
        $subClassData = $this->getSubClassLink($mainClass, $scheme);

        foreach ($subClassData as $class) {
            $liveType = $dom->createElement("liveType");
            $liveType->setAttribute("id", $class['id']);
            $liveType->setAttribute("name", $this->handleXml($class['name']));
            $liveType->setAttribute("eng_name", $this->handleXml($class['name']));
            $attributes->appendChild($liveType);

            foreach ($class['channels'] as $channel) {
                $channelDom = $dom->createElement("channel");
                $channelDom->setAttribute("epg", empty($channel['alias_name'])? 'null' : $channel['alias_name']);
                $channelDom->setAttribute("id", $channel['channel_number']);
                $channelDom->setAttribute("name", $this->handleXml($channel['zh_name']));
                $channelDom->setAttribute("eng_name", $this->handleXml($channel['name']));
                $channelDom->setAttribute("icon", $this->handleXml(Func::getAccessUrl($channel['image'], 86400 * 7)));
                $channelDom->setAttribute("number", "1");
                $channelDom->setAttribute("playback", "0");
                $liveType->appendChild($channelDom);

                foreach ($channel['links'] as $link) {
                    $addressInfo = $dom->createElement("addressInfo");
                    $addressInfo->setAttribute("urlType", "0");
                    $addressInfo->setAttribute("id", $link['id']);
                    $addressInfo->setAttribute("name", empty($link['source']) ? $link['source'] : 'default');
                    $addressInfo->setAttribute("decode", $link['decode']);
                    $addressInfo->setAttribute("definition", $link['definition']);
                    $addressInfo->setAttribute("method", $link['method']);
                    $addressInfo->setAttribute("ping", "7000");
                    $url = $dom->createCDATASection(trim($link['link']));
                    $addressInfo->appendChild($url);
                    $channelDom->appendChild($addressInfo);
                }

            }
        }

        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $redis->set("OTT_LIST_XML_{$mainClass->name}_{$schemeName}", $dom->saveXML());
        $redis->set("OTT_LIST_XML_{$mainClass->name}_{$schemeName}_VERSION", $version);

        return true;
    }


    public function handleXml($str)
    {
        str_replace('&', "&amp;", $str);
        str_replace('\'', "&apos;", $str);
        str_replace('"', "&quot;", $str);
        str_replace('<', "&lt;", $str);
        str_replace('>', "&gt;", $str);
        return $str;
    }

}