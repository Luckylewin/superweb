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
    public $type ;
    public $options;

    protected $model;

    public function __construct(Model $model, $type = 'video')
    {
       $this->model = $model;
       $this->type = $type;
    }

    public function setQueryOption($options)
    {
       $this->options = $options;
    }

    public function start()
    {
        $nextPage = true;
        while (!is_null($nextPage)) {
            if (isset($pageToken) && $pageToken) {
                $this->options['pageToken'] = $pageToken;
            }
            $searchResponse = $this->videoSearch($this->options);
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

        $this->videoSearch($optPara);
    }



    protected function getService()
    {
        $DEVELOPER_KEY = Yii::$app->params['YOUTUBE'];

        $client = new Google_Client();
        $client->setDeveloperKey($DEVELOPER_KEY);
        return $youtube = new Google_Service_YouTube($client);
    }

    /**
     * 影片搜索
     * @param $optPara
     * @return \Google_Service_YouTube_SearchListResponse
     */
    protected function videoSearch($optPara)
    {
        $youtube = $this->getService();

        try {

            $searchResponse = $youtube->search->listSearch('id,snippet', $optPara);

            echo "本次搜索总页数 : {$searchResponse['pageInfo']['totalResults']}\n";
            echo "nexPageToken : {$searchResponse['nextPageToken']}\n";
            echo "总数：" . count($searchResponse['items']) . "\n";

            foreach ($searchResponse['items'] as $searchResult) {
                //print_r($searchResult);
                switch ($searchResult['id']['kind']) {
                    case 'youtube#video':
                        if ($this->type == 'video')  $this->collectVideo($searchResult);
                        break;
                    case 'youtube#channel':
                        echo  $searchResult['snippet']['title'], $searchResult['id']['channelId'] , PHP_EOL;
                        break;
                    case 'youtube#playlist':
                        if ($this->type == 'playlist')  $this->collectVideo($searchResult);
                        echo $searchResult['snippet']['title'], $searchResult['id']['playlistId'] ,PHP_EOL;
                        break;
                }
            }

            return $searchResponse;

        } catch (Google_Service_Exception $e) {
            echo $e->getMessage(),PHP_EOL;
        } catch (Google_Exception $e) {
            echo $e->getMessage(),PHP_EOL;
        }
    }


    protected function collectVideo($searchResult)
    {
       $data['title'] = trim($searchResult['snippet']['title']);
       if (!empty($data['title'])) {
            $data['url'] = $searchResult['id']['videoId'];
            $data['image'] = $searchResult['snippet']['thumbnails']['high']['url'];
            $data['info'] = $searchResult['snippet']['description'];
            
            if (method_exists($this->model, 'collect') && !empty($data['url']) && !empty($data['title'])) {
                $this->model->collect($data, 'Youtube');
            }
       }
    }
}