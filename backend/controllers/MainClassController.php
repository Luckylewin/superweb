<?php

namespace backend\controllers;

use backend\components\MyRedis;
use Yii;
use common\models\MainClass;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * MainClassController implements the CRUD actions for MainClass model.
 */
class MainClassController extends BaseController
{
    /**
     * Lists all MainClass models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MainClass::find(),
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MainClass model.
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
     * Creates a new MainClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MainClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MainClass model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $field = Yii::$app->request->get('field');
            if ($field == 'sort') {
                  $model->sort = Yii::$app->request->post('sort');
                  $model->save(false);
            }
            return [
                'status' => 0
            ];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', '操作成功');
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MainClass model.
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
     * Finds the MainClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MainClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MainClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListCache($id)
    {
        $model = $this->findModel($id);
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $cacheKeys = $redis->keys("OTT_LIST_XML_{$model->name}*");
        $data = [];

        if (!empty($cacheKeys)) {
            foreach ($cacheKeys as $key => $redisKey) {
                if (strpos($redisKey, 'VERSION') == false) {
                    $data[] = ['id' => $key, 'key_name'=>$redisKey];
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'models' => $data,
            'pagination' => false,
            'sort' => [
                'attributes' => ['id', 'key_name']
            ]
        ]);

        return $this->render('list-cache', [
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionViewCache($key)
    {
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $cache = $redis->get($key);
        header("Content-type: application/xml");
        exit($cache);
    }

}
