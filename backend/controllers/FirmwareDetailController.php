<?php

namespace backend\controllers;

use Yii;
use backend\models\FirmwareDetail;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FirmwareDetailController implements the CRUD actions for FirmwareDetail model.
 */
class FirmwareDetailController extends BaseController
{

    public function actionIndex()
    {
        $firmware_id = Yii::$app->request->get('firmware_id');

        $query = FirmwareDetail::find()->where(['firmware_id' => $firmware_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($query->count() == 0) {
            return $this->redirect(Url::to(['firmware-detail/create', 'firmware_id' => $firmware_id]));
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'firmware_id' => $firmware_id
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
        $model = new FirmwareDetail();
        $model->firmware_id = Yii::$app->request->get('firmware_id');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(['firmware-detail/index', 'firmware_id' => $model->firmware_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', Yii::t('backend', 'Success'));
            return $this->redirect(['firmware-detail/index', 'firmware_id' => $model->firmware_id]);
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
        if (($model = FirmwareDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
