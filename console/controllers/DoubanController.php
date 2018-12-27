<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/25
 * Time: 14:31
 */

namespace console\controllers;
use yii\console\Controller;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;

class DoubanController extends Controller
{
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 2;  // 同时并发抓取

    private $users = [
        'CycloneAxe', 'appleboy', 'Aufree', 'lifesign',
        'overtrue', 'zhengjinghua', 'Luckylewin'
    ];


    public function actionStart()
    {
        $this->handle();
    }

    public function getProxy()
    {
        return "http://" . mt_rand(1,20) . "com";
    }

    public function handle()
    {
        $this->totalPageCount = count($this->users);

        $client = new Client();

        $requests = function ($total) use ($client) {
            foreach ($this->users as $key => $user) {
                $proxy = $this->getProxy();
                $uri = 'https://api.github.com/users/' . $user;
                yield function() use ($client, $uri, $proxy) {
                    echo "使用代理" . $proxy.PHP_EOL;
                    return $client->getAsync($uri);
                };
            }
        };

        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => $this->concurrency,

            'fulfilled'   => function ($response, $index){

                $res = json_decode($response->getBody()->getContents());
                $this->stdout("请求第 $index 个请求，用户 " . $this->users[$index] . " 的 Github ID 为：" .$res->id . PHP_EOL);
                $this->countedAndCheckEnded();
            },

            'rejected' => function ($reason, $index){
                $this->stdout("rejected" );
                $this->stdout("rejected reason: " . $reason );
                $this->countedAndCheckEnded();
            },
        ]);

        // 开始发送请求
        $promise = $pool->promise();
        $promise->wait();
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            $this->counter++;
            return;
        }

        $this->stdout("请求结束！");
    }

}