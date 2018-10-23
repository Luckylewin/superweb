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


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->setFlash('success', Yii::t('backend', 'Success'));
        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = OttOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
