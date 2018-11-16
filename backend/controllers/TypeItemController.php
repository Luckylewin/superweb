<?php

namespace backend\controllers;

use backend\models\IptvType;
use Yii;
use backend\models\IptvTypeItem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TypeItemController implements the CRUD actions for IptvTypeItem model.
 */
class TypeItemController extends BaseController
{
    public function actionIndex()
    {
        $type_id = Yii::$app->request->get('type_id');

        if ($type_id == false) {
            return $this->redirect(['vod-list/index']);
        }

        $this->session()->set('type_id', $type_id);

        $dataProvider = new ActiveDataProvider([
            'query' => IptvTypeItem::find()->where(['type_id' => $type_id]),
            'pagination' => ['pagesize' => 100],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                    'exist_num' => SORT_DESC,
                ]
            ]
        ]);

        $type = IptvType::findOne($type_id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'type' => $type
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
        $model = new IptvTypeItem();
        if ($this->getRequest()->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return ['status' => 'success'];
            }

            return ['status' => 'fail'];
        }

        $model->type_id = Yii::$app->request->get('type_id');
        $model->sort    = 0;
        $model->is_show = 1;

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }


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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->success('info');
        return $this->redirect(['index', 'type_id' => $this->session()->get('type_id')]);
    }


    protected function findModel($id)
    {
        if (($model = IptvTypeItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function jump($model, $status)
    {
        if ($this->session()->has('type_id')) {
            $this->setFlash($status, Yii::t('backend', 'Success'));
            return $this->redirect(['index', 'type_id' => $this->session()->get('type_id')]);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

}
