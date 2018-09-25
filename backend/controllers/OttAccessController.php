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

    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new OttAccess();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success('success');
            return $this->redirect(['view', $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success('success');
            return $this->redirect(['view', $id]);
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
        if (($model = OttAccess::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
