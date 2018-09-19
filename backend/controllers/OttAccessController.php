<?php

namespace backend\controllers;

use backend\models\search\OttAccessSearch;
use Yii;
use backend\models\OttAccess;
use yii\web\NotFoundHttpException;

/**
 * OttAccessController implements the CRUD actions for OttAccess model.
 */
class OttAccessController extends BaseController
{

    /**
     * Lists all OttAccess models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OttAccessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OttAccess model.
     * @param string $mac
     * @param string $genre
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($mac, $genre)
    {
        return $this->render('view', [
            'model' => $this->findModel($mac, $genre),
        ]);
    }

    /**
     * Creates a new OttAccess model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OttAccess();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success('success');
            return $this->redirect(['view', 'mac' => $model->mac, 'genre' => $model->genre]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OttAccess model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $mac
     * @param string $genre
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($mac, $genre)
    {
        $model = $this->findModel($mac, $genre);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success('success');
            return $this->redirect(['view', 'mac' => $model->mac, 'genre' => $model->genre]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($mac, $genre)
    {
        $this->findModel($mac, $genre)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OttAccess model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $mac
     * @param string $genre
     * @return OttAccess the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($mac, $genre)
    {
        if (($model = OttAccess::findOne(['mac' => $mac, 'genre' => $genre])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
