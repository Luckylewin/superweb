<?php

namespace backend\controllers;

use backend\components\MyRedis;
use backend\models\OttRecommend;
use common\components\Func;
use common\models\SubClass;
use Yii;
use common\models\OttChannel;
use common\models\search\OttChannelSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * OttChannelController implements the CRUD actions for OttChannel model.
 */
class OttChannelController extends BaseController
{
    public $mainClass;
    public $subClass;

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $sub_class_id = Yii::$app->request->get('sub-id');
        if ($sub_class_id) {
            $this->subClass = SubClass::find()->where(['id' => $sub_class_id])->one();
            $this->mainClass = $this->subClass->mainClass;
        }

        return true;

    }

    /**
     * Lists all OttChannel models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new OttChannelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mainClass' => $this->mainClass,
            'subClass' => $this->subClass
        ]);
    }

    /**
     * 更新推荐数据
     * @return array
     */
    public function actionUpdateRecommend()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $field = Yii::$app->request->post('field');
            $channel_id = Yii::$app->request->get('channel_id');
            $recommend = OttRecommend::findOne(['channel_id' => $channel_id]);
            $recommend->sort = Yii::$app->request->post('value');
            $recommend->save(false);
            $this->setFlash('info', '修改排序成功');
            return ['status' => 0];
        }
    }

    public function actionRecommend()
    {
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $version = $redis->get('OTT_RECOMMEND_VERSION');

        $searchModel = new OttChannelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [
            'is_recommend' => 1
        ]);

        return $this->render('recommend', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'version' => $version
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new OttChannel();
        $model->sub_class_id = $this->subClass->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['ott-channel/view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'mainClass' => $this->mainClass,
            'subClass' => $this->subClass
        ]);
    }


    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if (in_array(Yii::$app->request->get('field'), ['is_recommend'])) {
            $model->is_recommend = $model->is_recommend == 0 ? '1' : '0';
            $model->save(false);
            if ($model->is_recommend) {
                $msg = '推送到推荐列表成功';
                $recommend = new OttRecommend();
                $recommend->channel_id = $model->id;
                $recommend->save(false);
            } else {
                $msg = '取消推荐成功';
                try {
                    $recommend = OttRecommend::findOne(['channel_id' => $model->id]);
                    if ($recommend) {
                        $recommend->delete();
                    }
                } catch (\Exception $e) {

                }
            }

            $this->setFlash('info', $msg);
            return $this->redirect(Url::to(['ott-channel/update','id' => $model->id]));
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $field = Yii::$app->request->post('field');
            if (in_array($field, ['sort','zh_name', 'name', 'use_flag','keywords'])) {
                $model->$field = Yii::$app->request->post('value');
                $model->save(false);
            }

            return ['status' => 'success'];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', Yii::t('backend', 'Success'));

            return $this->redirect(Url::to(['ott-channel/update', 'id' => $model->id]));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $this->setFlash('success', Yii::t('backend', 'Success'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionBatchDelete()
    {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $id = explode(',', $id);
            OttChannel::deleteAll(['in', 'id', $id]);

            $this->setFlash('info', "批量删除成功");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the OttChannel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OttChannel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OttChannel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRecommendCache()
    {
        $recommendData = [];
        $channels = OttChannel::find()->where(['is_recommend'=>1])
                                      ->joinWith('recommend', true, 'INNER JOIN')
                                      ->all();

        foreach ($channels as $channel) {
            try {
                 $mainClassName = $channel->subClass->mainClass->name;
                 $links = ArrayHelper::toArray($channel->ownLink);
                 array_walk($links, function(&$v) {
                     unset($v['id'], $v['channel_id'], $v['source'], $v['sort'], $v['use_flag'], $v['format'], $v['script_deal']);
                 });
                 $channel = ArrayHelper::toArray($channel);
                 $channel['links'] = $links;
                 $channel['id'] = $channel['channel_number'];
                 unset($channel['sub_class_id'], $channel['alias_name'], $channel['is_recommend']);
                 $channel['mainClass'] = $mainClassName;
                 $recommendData[] = $channel;
            } catch (\Exception $e) {
                continue;
            }
        }

        if (empty($recommendData)) {
            $this->setFlash('error', '当前没有数据');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $cache['version'] = time();
        $cache['data'] = $recommendData;

        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $redis->set('OTT_RECOMMEND', Json::encode($cache));
        $redis->set('OTT_RECOMMEND_VERSION', $cache['version']);
        $this->setFlash('info', '生成缓存成功');

        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionDropDownList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return OttChannel::getDropdownList(Yii::$app->request->get('sub_class_id'));
    }

    /**
     * 全局搜索
     * @return string
     */
    public function actionGlobalSearch()
    {
        if (Yii::$app->request->get('search')) {
            $value = Yii::$app->request->get('value');
            return OttChannel::globalSearch($value);
        }
        return $this->render('global-search');
    }

}
