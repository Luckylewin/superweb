<?php

namespace backend\controllers;

use Yii;
use backend\models\PlayGroup;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * PlayGroupController implements the CRUD actions for PlayGroup model.
 */
class PlayGroupController extends BaseController
{

    /**
     * Lists all PlayGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $vod_id = Yii::$app->request->get('vod_id');
        $dataProvider = new ActiveDataProvider([
            'query' => PlayGroup::find()->where(['vod_id' => $vod_id]),
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single PlayGroup model.
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
     * Creates a new PlayGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new PlayGroup();
        $model->vod_id = Yii::$app->request->get('vod_id');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'vod_id' => $model->vod_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PlayGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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

    /**
     * Finds the PlayGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlayGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlayGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
