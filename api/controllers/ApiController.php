<?php
namespace api\controllers;


use common\models\Vod;
use common\models\VodList;
use Yii;


/**
 * Site controller
 */
class ApiController extends BaseController
{
    /**
     * Displays homepage.
     */
    public function actionIndex()
    {
       $data = Yii::$app->request->post();
       $this->response($data);
    }

    /**
     * 影片分类
     */
    public function actionTypes()
    {
        $data = VodList::find()->select(['list_id','list_name', 'list_ispay', 'list_price', 'list_description'])->asArray()->all();
        return $this->response($data);
    }

    public function actionVodlist($id=null, $perpage=null, $page=1)
    {
        if (!$perpage) {
            $perpage = 10;
        }

        $query = Vod::find()->select(['vod_id','vod_name', 'vod_content', 'vod_pic' ,'vod_ispay', 'vod_price', 'vod_area', 'vod_director', 'vod_actor', 'vod_addtime']);
        $offset = ($page - 1) * $perpage;

        if ($id) {
            $query->where(['vod_cid' => $id]);
        }

        $data = $query->offset($offset)->limit($perpage)->asArray()->all();

        return $this->response($data);
    }

    public function actionVod($id)
    {
        if (empty($id) || $id <= 0) {
            throw new \Exception("Invalid params");
        }
        $vod = Vod::find()
                    ->select(['vod_id','vod_name', 'vod_trysee','vod_content', 'vod_pic' ,'vod_ispay', 'vod_price', 'vod_area', 'vod_director', 'vod_actor', 'vod_addtime', 'vod_url'])
                    ->where(['vod_id' => $id])
                    ->asArray()
                    ->one();

        $vod['vod_url'] = 'http://img.ksbbs.com/asset/Mon_1703/eb048d7839442d0.mp4';

        return $this->response($vod);
    }



    public function actionBanners()
    {
        $banner = [
            [
                'image' => 'http://118.24.105.214/images/banner.jpg',
                'vod_id' => '1',
            ],
            [
                'image' => 'http://118.24.105.214/images/banner2.jpg',
                'vod_id' => '2',
            ],
            [
                'image' => 'http://118.24.105.214/images/banner3.jpg',
                'vod_id' => '3',
            ],
            [
                'image' => 'http://118.24.105.214/images/banner4.jpg',
                'vod_id' => '4',
            ]
        ];

        return $this->response($banner);
    }

}
