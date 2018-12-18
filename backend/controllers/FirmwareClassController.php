<?php

namespace backend\controllers;

use Yii;
use backend\models\FirmwareClass;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FirmwareClassController implements the CRUD actions for FirmwareClass model.
 */
class FirmwareClassController extends BaseController
{

    /**
     * Lists all FirmwareClass models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FirmwareClass::find()->with('detail'),
        ]);

        return $this->render('index', [
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
        $model = new FirmwareClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(['firmware-detail%2Fcreate','firmware_id' => $model->id]);
        }

        $dropDownList = FirmwareClass::getDropDownList();

        if (empty($dropDownList)) {
            $this->setFlash('error', '需要新增新的关联订单');

            return $this->redirect(Url::to(['dvb-order/create']));
        }

        return $this->render('create', [
            'model' => $model,
            'dropDownList' => $dropDownList
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(['firmware-class/index', 'id' => $model->id]);
        }

        $dropDownList = FirmwareClass::getDropDownList();

        return $this->render('update', [
            'model' => $model,
            'dropDownList' => $dropDownList
        ]);
    }

    /**
     * Deletes an existing FirmwareClass model.
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
     * Finds the FirmwareClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FirmwareClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FirmwareClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
