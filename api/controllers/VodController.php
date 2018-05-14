<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/22
 * Time: 12:14
 */

namespace api\controllers;

use api\components\Formatter;
use api\components\Response;
use common\models\BuyRecord;
use common\models\User;
use common\models\Vod;
use common\models\Vodlink;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;


class VodController extends ActiveController
{

    public $modelClass = 'common\models\Vod';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    public function actionView($id)
    {
        $vod = Vod::find()->where(['vod_id' => $id])->limit(1)->with('vodLinks')->one();

        if (is_null($vod)) {
            return Response::error(Formatter::NOT_FOUND);
        }

        $vod->is_buy = 0;

        if ( empty($vod->vod_url) ) {
            $vod->vod_url = 'http://img.ksbbs.com/asset/Mon_1703/05cacb4e02f9d9e.mp4';
        }

        if ( $access_token = \Yii::$app->request->get('access-token') ) {
            $user = User::findIdentityByAccessToken($access_token);
            //查询是否购买过此片
            if ($user) {
                $record = BuyRecord::findOne( ['user_id' => $user->id, 'vod_id' => $id, 'is_valid' => 1] );
                if ($record) {
                    $vod->is_buy = 1;
                }
            }
        }

        return $vod;
    }

    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $request =  \Yii::$app->request;
        $cid = $request->get('cid');
        $vod_name = $request->get('name', null);

        $fields = Vod::getFields();
        unset($fields[array_search('vod_url', $fields)]);

        $query = $cid ? Vod::find()->select($fields)->where(['vod_cid' => $cid]) : Vod::find()->select($fields);
        $query->andFilterWhere(['like', 'vod_name', $vod_name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $request->get('per_page', 12)
            ],
        ]);

        return $dataProvider;

    }

    /**
     * 首页
     * @return ActiveDataProvider
     */
    public function actionHome()
    {
        $request =  \Yii::$app->request;

        $per_page = $request->get('per_page', 12);
        $modelClass = $this->modelClass;

        $fields = Vod::getFields();
        unset($fields[array_search('vod_url', $fields)]);

        $dataProvider = new ActiveDataProvider([
            'query' =>  $modelClass::find()->select($fields)->where(['vod_home' => 1]),
            'pagination' => [
                'pageSize' => $per_page,
            ],
        ]);

        return $dataProvider;
    }

    public function actionLink($vod_id)
    {
      return  $links = Vodlink::find()->where(['video_id'=>$vod_id])->all();
    }

}