<?php

namespace backend\controllers;

use backend\models\OttEvent;
use backend\models\OttEventTeam;
use common\models\OttChannel;
use Yii;
use backend\models\MajorEvent;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MajorEventController implements the CRUD actions for MajorEvent model.
 */
class MajorEventController extends BaseController
{

    /**
     * Lists all MajorEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MajorEvent::find(),
            'sort' => [
                'defaultOrder' => [
                    'time' => SORT_ASC,
                    'sort' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MajorEvent model.
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
     * Creates a new MajorEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MajorEvent();

        $post = Yii::$app->request->post();


        if ($model->load($post) && $model->initData($post) && $model->save()) {
            $this->setFlash('success', '操作成功');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MajorEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //初始化日期
        if (Yii::$app->request->isGet) {
            $model->beforeUpdate($model);
        } else if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->initData($post) && $model->save()) {
                $this->setFlash('success', '操作成功');
                return $this->redirect(['index']);
            }

            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('update', [
            'model' => $model,
            'teams' => $model->teams
        ]);
    }

    /**
     * Deletes an existing MajorEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->setFlash('info', '操作成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the MajorEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MajorEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MajorEvent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBatchDelete()
    {
        $ids = Yii::$app->request->get('ids');

        if (!empty($ids)) {
            $ids = explode(',', $ids);
            MajorEvent::deleteAll(['in', 'id', $ids]);
            $this->setFlash('success', '操作成功');
            return $this->redirect(['major-event/index']);
        }
        $this->setFlash('error', '操作失败');
        return $this->redirect(['major-event/index']);
    }

}
