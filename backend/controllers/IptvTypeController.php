<?php

namespace backend\controllers;

use common\models\VodList;
use Yii;
use backend\models\IptvType;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * IptvTypeController implements the CRUD actions for IptvType model.
 */
class IptvTypeController extends BaseController
{
    public $list;

    /**
     * Lists all IptvType models.
     * @return mixed
     * @throws
     */
    public function actionIndex()
    {
        $vod_list_id = Yii::$app->request->get('list_id');

        if ($vod_list_id == false) {
            return $this->redirect(Url::to(['vod-list/index']));
        }

        $this->session()->set('vod_list_id', $vod_list_id);

        $this->list = VodList::findOne($vod_list_id);

        if (is_null($this->list)) {
            throw new NotFoundHttpException(Yii::t('backend', '404 Not Found'));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => IptvType::find()->where(['vod_list_id' => $vod_list_id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'list' => $this->list
        ]);
    }

    /**
     * Displays a single IptvType model.
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
     * Creates a new IptvType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IptvType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->vod_list_id = Yii::$app->request->get('vod_list_id');

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IptvType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'success'));
            if ($this->session()->has('vod_list_id')) {
                return $this->redirect(['iptv-type/index', 'list_id' => $this->session()->get('vod_list_id')]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing IptvType model.
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
     * Finds the IptvType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IptvType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IptvType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
