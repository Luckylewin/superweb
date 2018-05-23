<?php

namespace backend\controllers;

use backend\models\Scheme;
use Yii;
use common\models\OttLink;
use common\models\search\OttLinkSearch;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * OttLinkController implements the CRUD actions for OttLink model.
 */
class OttLinkController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * Lists all OttLink models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OttLinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Yii::$app->response->data = $dataProvider->getModels();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OttLink model.
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
     * Creates a new OttLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OttLink();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OttLink model.
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
            if ($field == 'use_flag') {
                $model->use_flag = $model->use_flag ? '0' : '1';
                $model->save(false);
                return [
                    'status' => 0,
                    'data' => ['use_flag' => $model->use_flag],
                    'msg' => $model->use_flag_status[$model->use_flag]
                ];
            } elseif ($field == 'scheme_id') {
                $scheme_id = Yii::$app->request->post('scheme');
                $count = Scheme::find()->count('id');
                if (count($scheme_id) == $count) {
                    $scheme_id = 'all';
                } else {
                    $scheme_id = implode(',', $scheme_id);
                }
                $model->scheme_id = $scheme_id;
                $model->save(false);

                return [
                    'status' => 0,
                    'data' => ['use_flag' => $model->use_flag],
                    'msg' => "修改成功"
                ];

            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OttLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => 0
            ];
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the OttLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OttLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OttLink::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
