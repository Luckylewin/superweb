<?php

namespace backend\controllers;

use backend\components\MyRedis;
use backend\models\search\ParadeSearch;
use common\models\OttChannel;
use Yii;
use backend\models\Parade;

use yii\helpers\Json;
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

    /**
     * Lists all Parade models.
     * @return mixed
     */
    public function actionIndex()
    {
        $cache = Yii::$app->cache;
        if ($cache->exists("parade-check-task") == false) {
            //删除旧的数据
            $date = date('Y-m-d',strtotime('-2 day'));
            Parade::deleteAll("parade_date <= '$date'");
            $cache->set("parade-check-task",true, 86400);
        }

        $searchModel = new ParadeSearch();
        $query = Parade::find()->groupBy('channel_name');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $query);
        $version = $cache->get($this->version);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'version' => $version
        ]);
    }

    /**
     * Displays a single Parade model.
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
        Yii::$app->session->setFlash('success', "操作成功");

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new Parade model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
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
                $this->setFlash('success', '添加成功');
                return $this->redirect(['parade/list', 'name' => $model->channel_name]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Parade model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Parade model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Parade model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Parade the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parade::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 生成缓存
     * @return \yii\web\Response
     */
    public function actionCreateCache()
    {
        //获取数据库中的节目数
        $data = Parade::find()
                        ->select('channel_name,channel_id')
                        ->groupBy('channel_name')
                        ->all();

        //三个时间段的条件
        $conditions = array(

            "1" => " parade_date <= '".date('Y-m-d')."' "  ,//1天
            "3" => " parade_date >= '".date('Y-m-d',strtotime('yesterday')) ."' AND parade_date <= '".date('Y-m-d',strtotime('+1 day'))."' "  ,//三天
            //"5" => " parade_date >= '".date('Y-m-d',strtotime('yesterday')) ."' AND parade_date <= '".date('Y-m-d',strtotime('+3 day'))."' "  ,//五天
            //"7" => " parade_date >= '".date('Y-m-d',strtotime('yesterday')) ."' AND parade_date <= '".date('Y-m-d',strtotime('+6 day'))."' "  ,//七天
        );

        $totalCache = 0;

        foreach ($data as  $channel) {
            $atLastOne = false;
            foreach ($conditions as $dayNum => $where) {
               
                //查一下有没有这么多天
                $dbData = Parade::find()
                                ->select('parade_date')
                                ->where(['channel_id' => $channel->channel_id])
                                ->andWhere($where)
                                ->groupBy('parade_date')
                                ->asArray()
                                ->all();

                if (count($dbData) < $dayNum) continue;

                $dbData = Parade::find()->where(['channel_id' => $channel['channel_id']])
                                        ->andWhere($where)
                                        ->select('channel_name,parade_date,parade_data')
                                        ->asArray()
                                        ->all();
                $this->_insertRedis($dayNum,$channel->channel_name,$dbData);
                $atLastOne = true;
            }
            $atLastOne && $totalCache++;
        }

        Yii::$app->cache->set($this->version, date('YmdHis'));
        Yii::$app->session->setFlash('success', "本次生成{$totalCache}个节目的预告缓存");

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * 写入Redis
     * @param $dayNum
     * @param $channel
     * @param $paradeData
     * @return bool
     */
    protected function _insertRedis($dayNum,$channel,$paradeData)
    {
        if (empty($paradeData)) {
            return false;
        }

        $data = array();
        $paradeData = $this->completeYesterday($paradeData);

        $timeIndex = ['Today','Tomorrow-1','Tomorrow-2','Tomorrow-3','Tomorrow-4','Tomorrow-5','Tomorrow-6'];
        if (!empty($paradeData)) {
            foreach ($paradeData as $key => $value) {

                $diff = (strtotime($value['parade_date']) - strtotime('today'))/86400;
                $diff = $diff >= 6 ? 6 : $diff;
                $index = $diff < 0?'Yesterday':$timeIndex[$diff];
                $parade = json_decode($value['parade_data'],true);
                $epg = array();

                if (!empty($parade)) {
                    foreach ($parade as $_parade) {
                        $epg[] = array('time'=>substr($_parade['parade_time'],0,5),'name'=>$_parade['parade_name']);
                    }
                    $data[$index] = array_values($epg);
                }

            }
        }
        if (empty($data['Tomorrow-1'])) {
            $data['Tomorrow-1'] = [];
        }
        $hash = json_encode(array(
            'channel' => $channel,
            'day' => $dayNum,
            'parade' => $data
        ));

        $key = str_replace(' ','_', $channel) . '_'.date('Ymd');
        $redis = MyRedis::init(MyRedis::REDIS_PARADE_CONTENT);
        $redis->set($key,$hash);

        return true;
    }


    /**
     *
     */
    protected function completeYesterday($data)
    {
        if (!empty($data)) {
            $yesterday = date('Y-m-d',strtotime('yesterday'));
            $flag = false;
            foreach ($data as $t) {
                if ($t['parade_date'] == $yesterday) {
                    $flag = true;
                }
            }
            if ($flag == false) {
                array_unshift($data,array(
                    'channel_name' => $data[0]['channel_name'],
                    'parade_date' => $yesterday,
                    'parade_data' => '{}'
                ));
            }
            return $data;
        }
        return false;
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
            $channel = OttChannel::findOne(Yii::$app->request->post('channel'));
            if ($channel) {
                $channel->alias_name = Yii::$app->request->post('alias_name');
                $channel->save(false);
                $this->setFlash('info', '操作成功');
                $this->redirect(Yii::$app->request->referrer);
            }
        }

        $alias_name = Yii::$app->request->get('id');
        return $this->renderAjax('bind', [
            'alias_name' => $alias_name
        ]);
    }

}
