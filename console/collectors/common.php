<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/1
 * Time: 16:46
 */

namespace console\collectors;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Yii;

class common
{

    public $isCached;
    public $httpClient;
    public $crawler;

    public function getCrawler()
    {
        if (empty($this->crawler)) {
            $this->crawler = new Crawler();
        }

        return clone $this->crawler;
    }

    public function getHttpClient()
    {
        if (empty($this->httpClient)) {
            $this->httpClient  = new Client();
        }

        return $this->httpClient;
    }

    public function getDomIsCached()
    {
        return $this->isCached;
    }

    /**
     * @param $time
     */
    public function goSleep($time)
    {
        if ($this->getDomIsCached() == false) {
            if (is_array($time)) {
                $time = mt_rand($time[0], $time[1]);
                echo "Zzz....睡眠{$time}s" . PHP_EOL;
                sleep($time);
            } else {
                echo "Zzz....睡眠{$time}s" . PHP_EOL;
                sleep($time);
            }
        }
    }

    public function getJson($url)
    {
        try {
            if (Yii::$app->cache->exists(md5($url))) {
                $this->isCached = true;
                $data = Yii::$app->cache->get(md5($url));
                Yii::$app->cache->set(md5($url), $data, 186400);
            } else {
                $this->isCached = false;
                $client  = new Client();
                $options = [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
                        'Referer'    => 'https://www.google.com/',
                        'Accept-Language' => 'en-US,en;q=0.5'
                    ],

                ];

                $result = $client->request('GET', $url, $options);
                $data = $result->getBody()->getContents();

                $this->isCached = false;
                if ($data) {
                    Yii::$app->cache->set(md5($url), $data, 86400);
                }
            }

            return json_decode($data);

        }catch (\Exception $e) {
            $this->debug($e);
            $this->goSleep(5);

            return false;
        }
    }

    /**
     * @param $url
     * @param string $format
     * @param string $charset
     * @param array $cookies
     * @return bool|Crawler
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDom($url, $format ='html', $charset = "UTF-8", $cookies = null)
    {
        try {
            if (Yii::$app->cache->exists(md5($url))) {
                $this->isCached = true;
                $data = Yii::$app->cache->get(md5($url));
                Yii::$app->cache->set(md5($url), $data, 386400);
            } else {
                $this->isCached = false;
                $client  = $this->getHttpClient();
                $options = [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
                        'Referer'    => 'https://www.google.com/',
                        'Accept-Language' => 'en-US,en;q=0.5'
                    ],

                ];

                if ($cookies) {
                    $options['cookies'] = $cookies;
                }

                $result = $client->request('GET', $url, $options);
                $data = $result->getBody()->getContents();

                $this->isCached = false;
                if ($data) {
                    Yii::$app->cache->set(md5($url), $data, 386400);
                }
            }
        } catch (\Exception $e) {
            $this->debug($e);
            $this->goSleep(5);
            $result = $this->getHttpClient()->request('GET', $url, $options);
            $data = $result->getBody()->getContents();
            if (empty($data)) {
                return false;
            }

        }

        if (empty($data)) {
            return false;
        }

        $dom = $this->getCrawler();
        if ($format == 'html') {
            $dom->addHtmlContent($data, $charset);
        } else {
            $dom->addXmlContent($data, $charset);
        }

        return $dom;
    }

    public function debug(\Exception $e)
    {
        echo "错误消息:" . $e->getMessage() . PHP_EOL;
        echo "错误代码"  . $e->getCode() . PHP_EOL;
        echo "错误文件"  . $e->getFile() . PHP_EOL;
        echo "错误行号"  . $e->getLine() . PHP_EOL;
    }

    public function color($text, $status = 'SUCCESS')
    {
        $status = strtoupper($status);

        switch($status) {
            case "SUCCESS":
                $out = "[1;32m"; //Green background
                break;
            case "ERROR":
                $out = "[1;31m"; //Red background
                break;
            case "WARNING":
                $out = "[1;33m"; //Yellow background
                break;
            case "INFO":
                $out = "[1;34m"; //Blue background
                break;
            default:
                $out = "[1;32m"; //Blue background
        }

        echo "\033$out" . "$text" . PHP_EOL . "\e[0m";
    }
}