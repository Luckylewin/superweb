<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/15
 * Time: 11:02
 */

namespace console\collectors\youtube;

use Yii;
use Google_Service_YouTube;
use Google_Client;
use Google_Service_Exception;
use Google_Exception;
use yii\base\Model;

class Searcher
{
    public $query;
    public $area;
    public $type;
    public $order;
    public $videoDuration;

    protected $model;

    public function __construct(Model $model)
    {
       $this->model = $model;
    }

    public function setQueryOption($query, $area, $order = 'relevance', $videoDuration = 'short', $type = 'video')
    {
        $this->query = $query;
        $this->query = $area;
        $this->order = $order;
        $this->videoDuration = $videoDuration;
        $this->type = $type;
    }

    public function start()
    {
        $query = $this->query;
        $type = $this->type;
        $order = $this->order;

        $nextPage = true;
        while (!is_null($nextPage)) {
            if (isset($pageToken) && $pageToken) {
                $optPara = array(
                    'q' => $query,//
                    'maxResults' => 50,
                    'pageToken' => $pageToken,
                    'type' => $type,
                    'order' => $order,
                    //'videoDuration' => 'short',
                );
            } else {
                $optPara = array(
                    'q' => $query,//
                    'maxResults' => 50,
                    'type' => 'video',
                    'order' => 'relevance',
                    //'videoDuration' => 'short',
                );
            }
            $searchResponse = $this->listSearch($optPara);
            $pageToken = $searchResponse['nextPageToken'];
            sleep(2);
        }
    }

    protected function token($limit, $page)
    {
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

    protected function collectChannel($channelId)
    {
        $optPara = array(
            'channelId' => $channelId,//
            'maxResults' => 50,
        );

        $this->listSearch($optPara);
    }


    protected function listSearch($optPara)
    {
        $DEVELOPER_KEY = Yii::$app->params['YOUTUBE'];

        $client = new Google_Client();
        $client->setDeveloperKey($DEVELOPER_KEY);
        $youtube = new Google_Service_YouTube($client);

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
                        $this->collectVideo($searchResult);
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

        } catch (Google_Service_Exception $e) {
            echo $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
        } catch (Google_Exception $e) {
            echo $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
        }
    }


    protected function collectVideo($searchResult)
    {
       $title = trim($searchResult['snippet']['title']);
       if (!empty($title)) {
            $url = $searchResult['id']['videoId'];
            $image = $searchResult['snippet']['thumbnails']['high']['url'];
            $info = $searchResult['snippet']['description'];
            $area = $this->area;

            if (method_exists($this->model, 'collect')) {
                $this->model->collect($url, $image, $info, $area);
            }
       }
    }
}