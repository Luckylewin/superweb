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
use backend\models\IptvType;
use backend\models\IptvTypeItem;
use common\models\BuyRecord;
use common\models\User;
use common\models\Vod;
use common\models\Vodlink;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;


class VodController extends ActiveController
{

    public $modelClass = 'common\models\Vod';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    
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
        $vod_type = $request->get('vod_type', null);
        $vod_year = $request->get('vod_year', null);
        $vod_area = $request->get('vod_area', null);
        $vod_language = $request->get('vod_language', null);

        $fields = Vod::getFields();
        unset($fields[array_search('vod_url', $fields)]);

        if ($cid) {
            $query = Vod::find()->select($fields)->filterWhere([
                    'vod_cid' => $cid,
                    'vod_year' => $vod_year,
                    'vod_area' => $vod_area,
                    'vod_language' => $vod_language
                ]
            );
        } else {
            $query = Vod::find()->select($fields)->filterWhere([
                    'vod_year' => $vod_year,
                    'vod_area' => $vod_area,
                    'vod_language' => $vod_language
                ]
            );;
        }

        $query->andFilterWhere(['like', 'vod_name', $vod_name])
              ->andFilterWhere(['like', 'vod_type', $vod_type]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'vod_addtime' => SORT_DESC,
                    'sort' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => $request->get('per-page', 12)
            ],
        ]);

        return $dataProvider;

    }


    public function actionCondition($vod_id)
    {
        $data = [];
        $list = IptvType::find()->where(['vod_list_id' => $vod_id])
                                ->orderBy('sort asc')
                                ->asArray()
                                ->all();

        if (empty($list)) {return [];}

        foreach ($list as $type) {
             $data[] = [
                 'name' => $type['name'],
                 'field' => $type['field'],
                 'items' => IptvTypeItem::find()->select('name,zh_name')->where(['type_id' => $type['id']])->orderBy('sort asc')->asArray()->all()
             ];
        }

        return $data;
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