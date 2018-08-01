<?php

namespace backend\controllers;

use backend\models\Scheme;
use Yii;
use common\models\OttLink;
use common\models\search\OttLinkSearch;
use yii\helpers\ArrayHelper;
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
        $schemesMap = Scheme::find()->select('id,schemeName')->all();
        $schemes = ArrayHelper::map($schemesMap, 'id', 'schemeName');
        if (Yii::$app->request->isAjax) {
            $model->scheme_id = ArrayHelper::getColumn($schemesMap, 'id');
            $model->channel_id = Yii::$app->request->get('id');
            return $this->renderAjax('create', ['model' => $model,'schemes' => $schemes]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', '添加链接成功');
            return $this->redirect(['ott-channel/index', 'sub-id' => $model->channel->sub_class_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'schemes' => $schemes
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
        $schemesMap = Scheme::find()->select('id,schemeName')->all();
        $schemes = ArrayHelper::map($schemesMap, 'id', 'schemeName');

        if (Yii::$app->request->isAjax && Yii::$app->request->get('modal')) {

            if ($model->scheme_id == 'all') {
                $model->scheme_id = ArrayHelper::getColumn($schemesMap, 'id');
            } else {
                $model->scheme_id = explode(',', $model->scheme_id);
            }
            $model->channel_id = Yii::$app->request->get('id');

            return $this->renderAjax('update', ['model' => $model,'schemes' => $schemes]);
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $field = Yii::$app->request->get('field');
            if ($field == 'use_flag') {
                $model->use_flag = $model->use_flag ? '0' : '1';
                $model->save(false);
                return [
                    'status' => 0,
                    'data' => ['use_flag' => $model->use_flag],
                    'msg' => Yii::t('backend', $model->use_flag_status[$model->use_flag])
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
            $this->setFlash('info', '修改成功');
            return $this->redirect(['ott-channel/index', 'sub-id' => $model->channel->sub_class_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'schemes' => $schemes
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
