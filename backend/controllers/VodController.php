<?php

namespace backend\controllers;

use common\models\VodList;
use Yii;
use common\models\Vod;
use common\models\search\VodSearch;
use yii\web\NotFoundHttpException;


/**
 * VodController implements the CRUD actions for Vod model.
 */
class VodController extends BaseController
{


    /**
     * Lists all Vod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vod model.
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
     * Creates a new Vod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vod();
        if ($cid = Yii::$app->request->get('vod_cid')) {
            $model->vod_cid = $cid;
            $vodList = VodList::findOne($cid);
            $model->vod_trysee = $vodList->list_trysee;
            $model->vod_price = $vodList->list_price;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vod_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Vod model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', '操作成功');
            return $this->redirect(['view', 'id' => $model->vod_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionPushHome($id,$action)
    {
        $model = $this->findModel($id);
        $model->vod_home = $action;
        $model->save(false);

        $this->setFlash('info', '操作成功');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Vod model.
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
     * Finds the Vod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vod::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
