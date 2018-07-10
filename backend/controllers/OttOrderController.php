<?php

namespace backend\controllers;

use Yii;
use backend\models\OttOrder;
use backend\models\OttOrderSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OttOrderController implements the CRUD actions for OttOrder model.
 */
class OttOrderController extends BaseController
{

    /**
     * Lists all OttOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OttOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing OttOrder model.
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
     * Finds the OttOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OttOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OttOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
