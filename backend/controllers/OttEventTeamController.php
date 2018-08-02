<?php

namespace backend\controllers;

use backend\models\OttEvent;
use Yii;
use backend\models\OttEventTeam;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * OttEventTeamController implements the CRUD actions for OttEventTeam model.
 */
class OttEventTeamController extends BaseController
{
    /**
     * Lists all OttEventTeam models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OttEventTeam::find()->where(['event_id' => Yii::$app->request->get('event_id')]),
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OttEventTeam model.
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
     * Creates a new OttEventTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OttEventTeam();

        if ($event_id = Yii::$app->request->get('event_id')) {
            $model->event_id = $event_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(Url::to(['ott-event-team/index', 'event_id' => $model->event_id]));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OttEventTeam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(Url::to(['ott-event-team/index', 'event_id' => $model->event_id]));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OttEventTeam model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model instanceof OttEventTeam) {
            $event_id = $model->event_id;
            $this->setFlash('info', Yii::t('backend', 'Success'));
            $model->delete();
            return $this->redirect(Url::to(['ott-event-team/index', 'event_id' => $event_id]));
        }


    }

    /**
     * Finds the OttEventTeam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OttEventTeam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OttEventTeam::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDropDownList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return OttEventTeam::getDropDownList(Yii::$app->request->get('event_id'));

    }
}
