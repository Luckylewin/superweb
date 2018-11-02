<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/1
 * Time: 16:46
 */

namespace console\collectors;

use console\components\MySnnopy;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Yii;

class common
{

    public $isCached;

    public function getDomIsCached()
    {
        return $this->isCached;
    }

    /**
     * @param $time
     */
    public function goSleep($time)
    {
        if (is_array($time)) {
            $time = mt_rand($time[0], $time[1]);
            echo "Zzz....睡眠{$time}s" . PHP_EOL;
            sleep($time);
        } else {
            echo "Zzz....睡眠{$time}s" . PHP_EOL;
            sleep($time);
        }
    }

    /**
     * @param $url
     * @param string $format
     * @param string $charset
     * @param bool $proxy
     * @return bool|Crawler
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDom($url, $format ='html', $charset = "UTF-8", $proxy = true)
    {
        try {
            if (Yii::$app->cache->exists(md5($url))) {
                $this->isCached = true;
                $data = Yii::$app->cache->get(md5($url));
            } else {
                $this->isCached = false;
                $client = new Client();
                if ($proxy) {
                    $result = $client->request('GET', $url, [
                        ['proxy' => 'tcp://127.0.0.1:8118']
                    ]);
                } else {
                    $result = $client->request('GET', $url);
                }

                $data = $result->getBody()->getContents();

                $this->isCached = false;
                if ($data) {
                    Yii::$app->cache->set(md5($url), $data, 86400);
                }
            }
        }catch (\Exception $e) {
            echo "错误消息:" . $e->getMessage() . PHP_EOL;
            echo "错误代码"  . $e->getCode() . PHP_EOL;
            echo "错误文件"  . $e->getFile() . PHP_EOL;
            echo "错误行号"  . $e->getLine() . PHP_EOL;
            $this->goSleep(5);

            return false;
        }

        if (empty($data)) {
            return false;
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