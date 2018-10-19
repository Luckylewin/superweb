<?php

namespace backend\controllers;

use backend\components\MyRedis;
use backend\models\search\ParadeSearch;
use common\models\MainClass;
use common\models\OttChannel;
use console\queues\CacheParadeJob;
use Yii;
use backend\models\Parade;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ParadeController implements the CRUD actions for Parade model.
 */
class ParadeController extends BaseController
{

    public $version = 'parade:version';


    public function actionIndex()
    {
        $searchModel = new ParadeSearch();
        $query = Parade::find()->groupBy('channel_name');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionListChannel($name)
    {
        $query = Parade::find()->where(['channel_name' => $name]);
        $data = $query->all();

        return $this->render('list', [
            'data' => $data,
            'channel' => $name
        ]);

    }

    public function actionBatchDelete($name)
    {
        Parade::deleteAll(['channel_name' => $name]);
        Yii::$app->session->setFlash('success', Yii::t('backend', 'Success'));

        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionCreate()
    {
        $model = new Parade();

        if ( $name = Yii::$app->request->get('name')) {
            $model->channel_name = $name;
        }

        if (Yii::$app->request->isPost) {
            $post = $_POST;
            $paradeData = [];
            foreach ($post['name'] as $key => $val) {
                $paradeData[] = [
                    'parade_time' => $post['hour'][$key] . ':' . $post['minute'][$key],
                    'parade_name' => $post['name'][$key]
                ];
            }

            $model->load(Yii::$app->request->post());
            $model->parade_data = json_encode($paradeData);
            $model->source = '手动添加';

            if ($model->save()) {
                $this->setFlash('success', Yii::t('backend', 'Success'));
                return $this->redirect(['parade/list', 'name' => $model->channel_name]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->rememberReferer();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($this->getLastPage());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Parade::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionClearCache()
    {
        $redis = MyRedis::init(MyRedis::REDIS_EPG);
        $mainClass = MainClass::find()->all();

        if ($mainClass) {
            foreach ($mainClass as $class) {
                $key = "ALL_" . strtoupper($class->name) . "_PARADE_LIST";
                $redis->del($key);
                if ($class->name != $class->list_name) {
                    $key = "ALL_" . strtoupper($class->list_name) . "_PARADE_LIST";
                    $redis->del($key);
                }

            }
        }

        $this->success();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     *
     */
    public function actionValidateForm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Parade();
        $model->load(Yii::$app->request->post());
        return ActiveForm::validate($model);
    }

    public function actionBind()
    {
        if (Yii::$app->request->isPost) {
            $channel = OttChannel::findOne(Yii::$app->request->post('channel_id'));
            if ($channel) {
                $channel->alias_name = Yii::$app->request->post('alias_name');
                $channel->save(false);
                $this->setFlash('info', Yii::t('backend', 'Success'));
                $this->redirect(Url::to(['parade/index']));
            }
        }

        return $this->renderAjax('bind');
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

        $alias_name = Yii::$app->request->get('id');
        return $this->renderAjax('global-search', [
            'alias_name' => $alias_name
        ]);
    }

    /**
     *
     */
    public function actionCreateCache()
    {
        Yii::$app->queue->push(new CacheParadeJob());

        $this->setFlash('info', Yii::t('backend', 'Creating cache in the background'));
        return $this->redirect(Yii::$app->request->referrer);
    }

}
