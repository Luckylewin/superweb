<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/14
 * Time: 11:05
 */

namespace api\controllers;


use common\models\Vodlink;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class VodLinkController extends ActiveController
{
    public $modelClass = 'common\models\Vodlink';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'only' => ['index']
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $vod_id = \Yii::$app->request->get('vod_id');

        $dataProvider = new ActiveDataProvider([
            'query' => Vodlink::find()->where(['video_id' => $vod_id]),
            'pagination' => [
                'pageSize' => \Yii::$app->request->get('per_page', 20)
            ]
        ]);

        return $dataProvider;
    }

    public function actionView($id)
    {

        $link = Vodlink::findOne(['video_id' => $id]);

        return $link;
    }
}