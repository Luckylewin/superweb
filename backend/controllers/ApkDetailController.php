<?php

namespace backend\controllers;

use common\components\Func;
use Yii;
use common\oss\Aliyunoss;
use backend\models\ApkList;
use backend\models\ApkDetail;
use backend\models\search\ApkDetailSearch;
use yii\web\NotFoundHttpException;

/**
 * ApkDetailController implements the CRUD actions for ApkDetail model.
 */
class ApkDetailController extends BaseController
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $id = Yii::$app->request->get('id');

        $searchModel = new ApkDetailSearch();
        $query =  ApkDetail::find()->where(['apk_ID' => $id]);
        $dataProvider = $searchModel->search([], $query);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'id' => $id
        ]);
    }

    /**
     * Displays a single ApkDetail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->url = Func::getAccessUrl($model->url, 3600);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ApkDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $apkModel = ApkList::findOne($id);
        $model = new ApkDetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        }

        return $this->render('create', [
            'model' => $model,
            'apkModel' => $apkModel
        ]);
    }

    /**
     * Updates an existing ApkDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $apkModel = ApkList::findOne($model->apk_ID);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        }

        return $this->render('update', [
            'model' => $model,
            'apkModel' => $apkModel
        ]);
    }

    /**
     * Deletes an existing ApkDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $apk_ID = $model->apk_ID;
        $model->delete();

        Yii::$app->session->setFlash('success', '操作成功');

        return $this->redirect(['apk-detail/index', 'id' => $apk_ID]);
    }

    /**
     * Finds the ApkDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ApkDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ApkDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
