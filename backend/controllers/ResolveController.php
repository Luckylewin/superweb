<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use backend\components\MyRedis;
use backend\models\IptvUrlResolution;


/**
 * ResolveController implements the CRUD actions for IptvUrlResolution model.
 */
class ResolveController extends BaseController
{
    const CACHE = 'resolve_list';

    /**
     * Lists all IptvUrlResolution models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => IptvUrlResolution::find(),
        ]);

        $redis = (MyRedis::init());
        $redis->select(MyRedis::REDIS_PROTOCOL);
        $cache = $redis->get(self::CACHE);

        if (!empty($cache) && $cache = json_decode($cache, true)) {
            $cache = $cache['version'];
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'version' => $cache
        ]);
    }

    /**
     * Displays a single IptvUrlResolution model.
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

    /**
     * Creates a new IptvUrlResolution model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IptvUrlResolution();
        $data = Yii::$app->request->post($model->formName());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IptvUrlResolution model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $fields = ['c', 'android'];
        foreach ($fields as $field) {
            $data = json_decode($model->$field, true);
            foreach($data['regex'] as $key => $value) {
                $modelField = $field . '_' . $key;
                $model->$modelField = $value + ["","",""];

            }
        }

        if (Yii::$app->request->isPost) {
           // print_r(Yii::$app->request->post());
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing IptvUrlResolution model.
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
     * Finds the IptvUrlResolution model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IptvUrlResolution the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IptvUrlResolution::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 生成缓存
     */
    public function actionCache()
    {
       $redis = MyRedis::init();
       $regex = ArrayHelper::toArray(IptvUrlResolution::find()->all());
       foreach ($regex as $key => $value) {
           $regex[$key]['c'] = json_decode($regex[$key]['c'], true);
           $regex[$key]['android'] = json_decode($regex[$key]['android'], true);
       }

       $redis->select(MyRedis::REDIS_PROTOCOL);

       $data['version'] = date('YmdHis');
       $data['data'] = $regex;

       $redis->set(self::CACHE, json_encode($data));

       Yii::$app->session->setFlash('success', '操作成功');
       return $this->redirect(['resolve/index']);
    }

}
