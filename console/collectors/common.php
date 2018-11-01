<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/1
 * Time: 16:46
 */

namespace console\collectors;

use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use Yii;

class common
{

    /**
     * 获取dom
     * @param $url
     * @param string $format
     * @param string $charset
     * @return Crawler
     * @throws \Exception
     */
    public function getDom($url, $format='html', $charset="UTF-8")
    {
        if (Yii::$app->cache->exists(md5($url))) {
            $data = Yii::$app->cache->get(md5($url));
        } else {
            $snnopy = MySnnopy::init();
            $snnopy->fetch($url);
            $data = $snnopy->results;
            if ($data) {
                Yii::$app->cache->set(md5($url), $data, 1800);
            }
        }

        if (empty($data)) {
            throw new \Exception("没有数据");
        }

        $dom = new Crawler();
        if ($format == 'html') {
            $dom->addHtmlContent($data, $charset);
        } else {
            $dom->addXmlContent($data, $charset);
        }

        return $dom;
    }
}