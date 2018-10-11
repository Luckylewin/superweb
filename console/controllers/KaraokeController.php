<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/11
 * Time: 18:17
 */

namespace console\controllers;


use backend\models\Karaoke;
use yii\console\Controller;

class KaraokeController extends Controller
{
    public function actionSearch()
    {
        $nextPage = true;
        while (!is_null($nextPage)) {
            if (isset($pageToken) && $pageToken) {
                $optPara = array(
                    'q' => 'karaoke beat chuẩn',//
                    'maxResults' => 50,
                    'pageToken' => $pageToken,
                    'type' => 'video',
                    'order' => 'relevance',
                    //'videoDuration' => 'short',

                );
            } else {
                $optPara = array(
                    'q' => 'karaoke beat chuẩn',//
                    'maxResults' => 50,
                    //'type' => 'video',
                    'order' => 'relevance',
                    //'videoDuration' => 'short',

                );
            }
            $searchResponse = $this->listSearch($optPara);
            $pageToken = $searchResponse['nextPageToken'];
            sleep(2);
        }
    }

    function token($limit, $page) {
        $start = 1 + ($page - 1) * $limit;
        $third_chars = array_merge(
            range("A","Z",4),
            range("c","z",4),
            range(0,9,4));
        return 'C'.
            chr(ord('A') + floor($start / 16)).
            $third_chars[($start % 16) - 1].
            'QAA';
    }

    public function collectChannel($channelId)
    {
        $optPara = array(
            'channelId' => $channelId,//
            'maxResults' => 50,
        );

        $this->listSearch($optPara);
    }


    public function listSearch($optPara)
    {
        $DEVELOPER_KEY = 'AIzaSyAZ4FSwFjVndaMgRy2OkCxqNvmHgYhIwB4';

        $client = new \Google_Client();
        $client->setDeveloperKey($DEVELOPER_KEY);

        // Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($client);

        $htmlBody = '';
        $channels = '';
        $playlists = '';

        try {

            $searchResponse = $youtube->search->listSearch('id,snippet', $optPara);

            echo "本次搜索总页数 : {$searchResponse['pageInfo']['totalResults']}\n";
            echo "nexPageToken : {$searchResponse['nextPageToken']}\n";
            echo "总数：" . count($searchResponse['items']) . "\n";

            foreach ($searchResponse['items'] as $searchResult) {
                //print_r($searchResult);
                switch ($searchResult['id']['kind']) {
                    case 'youtube#video':
                        $result = $this->collectVideo($searchResult);
                        break;
                    case 'youtube#channel':
                        $channels .= sprintf('<li>%s (%s)</li>',
                            $searchResult['snippet']['title'], $searchResult['id']['channelId']);
                        break;
                    case 'youtube#playlist':
                        $playlists .= sprintf('<li>%s (%s)</li>',
                            $searchResult['snippet']['title'], $searchResult['id']['playlistId']);
                        break;
                }
            }

            return $searchResponse;

        } catch (\Google_Service_Exception $e) {
            echo $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
        } catch (\Google_Exception $e) {
            echo $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
        }
    }

    public function collectVideo($searchResult)
    {
        $data = Karaoke::findOne(['albumName' => $searchResult['snippet']['title']]);

        if (empty($data)) {
            $searchResult['snippet']['title'] = trim($searchResult['snippet']['title']);
            $karaoke = new Karaoke();
            if (!empty($searchResult['snippet']['title']) && !empty($searchResult['snippet']['title'])) {
                $karaoke->url = $searchResult['id']['videoId'];
                $karaoke->albumName = $searchResult['snippet']['title'];
                $karaoke->albumImage = $searchResult['snippet']['thumbnails']['high']['url'];
                $karaoke->info = $searchResult['snippet']['description'];
                $karaoke->save(false);
                $this->stdout("新增" . $searchResult['snippet']['title'] . PHP_EOL);
            }
        } else {
            $this->stdout($searchResult['snippet']['title'] . "已经存在\n");
        }

        return false;
    }

}