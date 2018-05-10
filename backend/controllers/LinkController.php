<?php

namespace backend\controllers;

use common\models\Vod;
use Yii;
use common\models\Vodlink;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * LinkController implements the CRUD actions for Vodlink model.
 */
class LinkController extends BaseController
{
    public $vod;

    public function beforeAction($action)
    {
        $vod_id = Yii::$app->request->get('vod_id', false);
        if (in_array($action->id, ['index', 'view'])) {
            if ($vod_id == false) {
                throw new NotFoundHttpException('发生了错误');
            }
        }
        $this->vod = Vod::findOne($vod_id);

        return parent::beforeAction($action);
    }

    /**
     * Lists all Vodlink models.
     * @return mixed
     * @throws NotFoundHttpException if the vod_id cannot be found
     */
    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Vodlink::find()->where(['video_id' => $this->vod->vod_id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'vod' => $this->vod
        ]);
    }

    /**
     * Displays a single Vodlink model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'vod' => $this->vod
        ]);
    }

    /**
     * Creates a new Vodlink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vodlink();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'vod_id' => $this->vod->vod_id]);
        }

        $model->episode = $model->getNextEpisode($this->vod->vod_id);
        $model->season = $model->getNextSeason($this->vod->vod_id);

        return $this->render('create', [
            'model' => $model,
            'vod' => $this->vod
        ]);
    }

    /**
     * Updates an existing Vodlink model.
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
            'vod' => $this->vod
        ]);
    }

    /**
     * Deletes an existing Vodlink model.
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
     * Finds the Vodlink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vodlink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vodlink::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
