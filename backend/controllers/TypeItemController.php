<?php

namespace backend\controllers;

use backend\models\IptvType;
use Yii;
use backend\models\IptvTypeItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TypeItemController implements the CRUD actions for IptvTypeItem model.
 */
class TypeItemController extends BaseController
{

    /**
     * Lists all IptvTypeItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $type_id = Yii::$app->request->get('type_id');

        if ($type_id == false) {
            return $this->redirect(['vod-list/index']);
        }

        $this->session()->set('type_id', $type_id);

        $dataProvider = new ActiveDataProvider([
            'query' => IptvTypeItem::find()->where(['type_id' => $type_id]),
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC
                ]
            ]
        ]);

        $type = IptvType::findOne($type_id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'type' => $type
        ]);
    }

    /**
     * Displays a single IptvTypeItem model.
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
     * Creates a new IptvTypeItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IptvTypeItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->jump($model, 'success');
        }

        $model->type_id = Yii::$app->request->get('type_id');

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IptvTypeItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->jump($model, 'info');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing IptvTypeItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'type_id' => $this->session()->get('type_id')]);
    }

    /**
     * Finds the IptvTypeItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IptvTypeItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IptvTypeItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $model IptvTypeItem
     * @return \yii\web\Response
     */
    protected function jump($model, $status)
    {
        if ($this->session()->has('type_id')) {
            $this->setFlash($status, Yii::t('backend', 'Success'));
            return $this->redirect(['index', 'type_id' => $this->session()->get('type_id')]);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
}
