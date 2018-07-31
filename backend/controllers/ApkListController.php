<?php

namespace backend\controllers;

use Yii;
use backend\models\ApkList;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use backend\models\search\ApkListSearch;

/**
 * ApkListController implements the CRUD actions for ApkList model.
 */
class ApkListController extends BaseController
{

    /**
     * Lists all ApkList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApkListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ApkList model.
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
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ApkList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['view', 'id' => $model->ID]);
        }
        if (!empty($model->scheme_id)) {
            $model->scheme_id = explode(',', $model->scheme_id);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', '操作成功');
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return ApkList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ApkList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
