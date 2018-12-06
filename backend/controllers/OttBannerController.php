<?php

namespace backend\controllers;

use backend\components\MyRedis;
use Yii;
use backend\models\OttBanner;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OttBannerController implements the CRUD actions for OttBanner model.
 */
class OttBannerController extends BaseController
{
    /**
     * Lists all OttBanner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $version = $redis->get('OTT_BANNERS_VERSION');

        $dataProvider = new ActiveDataProvider([
            'query' => OttBanner::find(),
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'version' => $version
        ]);
    }

    /**
     * Displays a single OttBanner model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $this->rememberReferer();
        $model = new OttBanner();
        if ($channel_id = Yii::$app->request->get('channel_id')) {
            $model->channel_id = $channel_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(['ott-banner/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model) {
        
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OttBanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->setFlash('info', '删除成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the OttBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OttBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OttBanner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateCache()
    {
        $banners = [];
        $bannerData = OttBanner::find()->orderBy('sort')->with('channel')->all();

        foreach ($bannerData as $banner) {
            try {
                $channels = $banner->channel;
                $channel = ArrayHelper::toArray($channels);
                $channel['mainClass'] = $banner->channel->subClass->mainClass->name;
                $links = ArrayHelper::toArray($channels->ownLink);
                array_walk($links, function(&$v) {
                    unset($v['id'], $v['channel_id'], $v['source'], $v['sort'], $v['use_flag'], $v['format'], $v['script_deal']);
                });
                $channel['links'] = $links;
                $channel['id'] = $channel['channel_number'];
                unset($channel['sub_class_id'], $channel['alias_name'], $channel['is_recommend']);
                $banner = ArrayHelper::toArray($banner);
                $banner['channels'] = $channel;
                $banners[] = $banner;
            }catch (\Exception $e) {
                continue;
            }
        }

        if (empty($banners)) {
            $this->setFlash('error', '当前没有数据');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $cache['version'] = time();
        $cache['data'] = $banners;

        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $redis->set('OTT_BANNERS', Json::encode($cache));
        $redis->set('OTT_BANNERS_VERSION', $cache['version']);
        $this->setFlash('info', '生成缓存成功');

        return $this->redirect(Yii::$app->request->referrer);
    }
}
